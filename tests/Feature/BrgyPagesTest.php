<?php

namespace Tests\Feature;

use App\Models\Barangay;
use App\Models\Municipality;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BrgyPagesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Virac', 'San Andres', 'Bato'] as $name) {
            Municipality::firstOrCreate(['name' => $name]);
        }

        $barangays = [
            ['brgy_name' => 'San Isidro Village', 'city' => 'Virac', 'population' => 1200],
            ['brgy_name' => 'Sabangan', 'city' => 'Virac', 'population' => 2600],
            ['brgy_name' => 'San Andres Proper', 'city' => 'San Andres', 'population' => 2100],
            ['brgy_name' => 'Codon', 'city' => 'San Andres', 'population' => 3100],
            ['brgy_name' => 'Bato Central', 'city' => 'Bato', 'population' => 1500],
            ['brgy_name' => 'Pangcayanan', 'city' => 'Bato', 'population' => 2800],
        ];

        foreach ($barangays as $barangay) {
            Barangay::updateOrCreate(
                ['brgy_name' => $barangay['brgy_name']],
                $barangay + ['household' => 100, 'has_health_center' => false]
            );
        }
    }

    public function test_barangay_list_displays_records_from_the_database(): void
    {
        $this->get('/brgys')
            ->assertOk()
            ->assertSee('Bato Central')
            ->assertSee('page=2', false);

        $this->get('/brgys?page=2')
            ->assertOk()
            ->assertDontSee('Bato Central');
    }

    public function test_barangay_detail_displays_the_requested_record(): void
    {
        $this->get('/brgys/San%20Isidro%20Village')
            ->assertOk()
            ->assertSee('San Isidro Village')
            ->assertSee('Virac');
    }

    public function test_barangay_list_can_be_filtered_by_municipality(): void
    {
        $this->get('/brgys?municipality=San%20Andres')
            ->assertOk()
            ->assertSee('San Andres Proper')
            ->assertSee('Codon')
            ->assertDontSee('San Isidro Village');
    }

    public function test_barangay_list_uses_municipality_ids_in_filter_form(): void
    {
        $municipalities = Municipality::query()->orderBy('id')->get();
        $this->assertNotEmpty($municipalities);

        $response = $this->get('/brgys')->assertOk();
        $response->assertSee('method="GET"', false)
            ->assertSee('action="'.route('brgys.index').'"', false)
            ->assertSee('name="search"', false)
            ->assertSee('name="has_health_center"', false)
            ->assertSee('href="'.route('brgys.index').'" class="btn btn-outline-secondary">Clear</a>', false);

        foreach ($municipalities as $municipality) {
            $response->assertSee($municipality->name)
                ->assertSee('value="'.$municipality->getKey().'"', false);
        }

        $municipality = $municipalities->first();
        $this->get('/brgys?municipality='.$municipality->getKey().'&population_group=all')
            ->assertOk()
            ->assertSee('matching barangays');
    }

    public function test_barangay_search_and_boolean_filter_work_alone_and_together(): void
    {
        $virac = Municipality::where('name', 'Virac')->firstOrFail();
        $bato = Municipality::where('name', 'Bato')->firstOrFail();
        $records = [
            ['brgy_name' => 'FilterFixture Health Center', 'city' => $virac->name, 'has_health_center' => true],
            ['brgy_name' => 'FilterFixture No Center', 'city' => $virac->name, 'has_health_center' => false],
            ['brgy_name' => 'FilterFixture Other City', 'city' => $bato->name, 'has_health_center' => true],
        ];

        foreach ($records as $record) {
            Barangay::updateOrCreate(
                ['brgy_name' => $record['brgy_name']],
                $record + ['population' => 1000, 'household' => 100]
            );
        }

        $this->get('/brgys?search=FilterFixture')
            ->assertOk()
            ->assertSee('FilterFixture Health Center')
            ->assertSee('FilterFixture No Center');

        $this->get('/brgys?municipality='.$virac->getKey().'&search=FilterFixture')
            ->assertOk()
            ->assertSee('FilterFixture Health Center')
            ->assertSee('FilterFixture No Center')
            ->assertDontSee('FilterFixture Other City');

        $this->get('/brgys?search=FilterFixture&municipality='.$virac->getKey().'&has_health_center=1')
            ->assertOk()
            ->assertSee('FilterFixture Health Center')
            ->assertDontSee('FilterFixture No Center')
            ->assertDontSee('FilterFixture Other City')
            ->assertSee('value="FilterFixture"', false)
            ->assertSee('value="'.$virac->getKey().'" selected', false)
            ->assertSee('value="1" selected', false);

        $this->get('/brgys?search=FilterFixture&has_health_center=0')
            ->assertOk()
            ->assertSee('FilterFixture No Center')
            ->assertDontSee('FilterFixture Health Center')
            ->assertDontSee('FilterFixture Other City')
            ->assertSee('value="0" selected', false);
    }

    public function test_pagination_keeps_filters_and_sort_and_invalid_sort_falls_back(): void
    {
        $virac = Municipality::where('name', 'Virac')->firstOrFail();
        for ($number = 1; $number <= 8; $number++) {
            Barangay::updateOrCreate(
                ['brgy_name' => sprintf('PagingFixture %02d', $number)],
                [
                    'city' => $virac->name,
                    'population' => $number,
                    'household' => 100,
                    'has_health_center' => true,
                ]
            );
        }

        $query = http_build_query([
            'search' => 'PagingFixture',
            'municipality' => $virac->getKey(),
            'has_health_center' => '1',
            'sort' => 'population',
        ]);
        $this->get('/brgys?'.$query)
            ->assertOk()
            ->assertSee('search=PagingFixture', false)
            ->assertSee('municipality='.$virac->getKey(), false)
            ->assertSee('has_health_center=1', false)
            ->assertSee('sort=population', false)
            ->assertSee('page=2', false);

        $this->get('/brgys?'.$query.'&page=2')
            ->assertOk()
            ->assertSee('PagingFixture 08')
            ->assertSee('value="population" selected', false)
            ->assertSee('value="1" selected', false);

        foreach ([
            ['brgy_name' => 'SortFixture A', 'population' => 200],
            ['brgy_name' => 'SortFixture B', 'population' => 100],
        ] as $record) {
            Barangay::updateOrCreate(
                ['brgy_name' => $record['brgy_name']],
                $record + ['city' => $virac->name, 'household' => 100, 'has_health_center' => true]
            );
        }

        $byName = $this->get('/brgys?search=SortFixture&sort=brgy_name')
            ->assertOk()
            ->getContent();
        $byPopulation = $this->get('/brgys?search=SortFixture&sort=population')
            ->assertOk()
            ->getContent();
        $invalidSort = $this->get('/brgys?search=SortFixture&sort=brgy_nam')
            ->assertOk()
            ->getContent();

        $this->assertLessThan(strpos($byName, 'SortFixture B'), strpos($byName, 'SortFixture A'));
        $this->assertLessThan(strpos($byPopulation, 'SortFixture A'), strpos($byPopulation, 'SortFixture B'));
        $this->assertLessThan(strpos($invalidSort, 'SortFixture B'), strpos($invalidSort, 'SortFixture A'));
    }

    public function test_navigation_links_to_home_and_keeps_barangays_active_on_detail(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.route('home').'">Barangays in Catanduanes</a>', false)
            ->assertSee('href="'.route('home').'">Home</a>', false)
            ->assertSee('href="'.route('brgys.index').'">Barangays</a>', false);

        $this->get('/brgys/San%20Isidro%20Village')
            ->assertOk()
            ->assertSee('class="nav-link active"', false)
            ->assertSee('href="'.route('brgys.index').'">Barangays</a>', false);
    }

    public function test_create_form_contains_every_barangay_field(): void
    {
        $municipality = Municipality::query()->orderBy('name')->first();

        $this->get('/brgys/create')
            ->assertOk()
            ->assertSee('name="name"', false)
            ->assertSee('name="municipality"', false)
            ->assertSee('name="population"', false)
            ->assertSee('name="household"', false)
            ->assertSee('name="has_health_center"', false)
            ->assertSee('action="'.route('brgys.store').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('value="'.$municipality->name.'"', false)
            ->assertSee('>'.$municipality->name.'</option>', false)
            ->assertSee('>Virac</option>', false)
            ->assertSee('>San Andres</option>', false)
            ->assertSee('>Bato</option>', false);
    }

    public function test_invalid_barangay_submission_does_not_save(): void
    {
        $this->from('/brgys/create')
            ->post('/brgys', [
                'name' => 'Invalid Barangay',
                'municipality' => 'Virac',
                'population' => 'not-a-number',
                'household' => '',
                'has_health_center' => '0',
            ])
            ->assertRedirect('/brgys/create')
            ->assertSessionHasErrors('population');

        $this->assertDatabaseMissing('brgys', ['brgy_name' => 'Invalid Barangay']);
    }

    public function test_creating_barangay_updates_the_live_home_count(): void
    {
        $countBefore = Barangay::count();
        $name = 'Count Test Barangay';

        try {
            $this->get('/')
                ->assertOk()
                ->assertSee('municipalities')
                ->assertSee('barangays')
                ->assertSee('<div class="display-6 fw-bold">'.$countBefore.'</div>', false);

            $this->post('/brgys', [
                'name' => $name,
                'municipality' => 'Virac',
                'population' => 3000,
                'household' => 700,
                'has_health_center' => '0',
            ])->assertRedirect(route('brgys.index'));

            $this->assertSame($countBefore + 1, Barangay::count());

            $this->get('/')
                ->assertOk()
                ->assertSee('<div class="display-6 fw-bold">'.($countBefore + 1).'</div>', false);
        } finally {
            Barangay::where('brgy_name', $name)->delete();
        }
    }
}
