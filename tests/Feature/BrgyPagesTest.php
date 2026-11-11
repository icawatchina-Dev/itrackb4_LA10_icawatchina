<?php

namespace Tests\Feature;

use App\Models\Municipality;
use Tests\TestCase;

class BrgyPagesTest extends TestCase
{
    private string $originalBarangays;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalBarangays = file_get_contents(storage_path('app/private/barangays.json'));
    }

    protected function tearDown(): void
    {
        file_put_contents(storage_path('app/private/barangays.json'), $this->originalBarangays);

        parent::tearDown();
    }

    public function test_barangay_list_displays_records_from_storage(): void
    {
        $this->get('/brgys')
            ->assertOk()
            ->assertSee('San Isidro Village')
            ->assertSee('Sabangan');
    }

    public function test_barangay_detail_displays_the_requested_record(): void
    {
        $this->get('/brgys/1')
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
            ->assertSee('<td>3</td>', false)
            ->assertDontSee('San Isidro Village');
    }

    public function test_barangay_list_uses_municipality_ids_in_filter_links(): void
    {
        $municipalities = Municipality::query()->orderBy('id')->get();
        $this->assertNotEmpty($municipalities);

        $response = $this->get('/brgys')->assertOk();

        foreach ($municipalities as $municipality) {
            $response->assertSee($municipality->name)
                ->assertSee(
                    'municipality='.$municipality->getKey().'&amp;population_group=all',
                    false
                );
        }

        $municipality = $municipalities->first();
        $this->get('/brgys?municipality='.$municipality->getKey().'&population_group=all')
            ->assertOk()
            ->assertSee('Showing barangays in '.$municipality->name);
    }

    public function test_create_form_contains_every_barangay_field(): void
    {
        $municipality = Municipality::query()->orderBy('name')->first();
        $this->assertNotNull($municipality);
        $municipalityNames = Municipality::query()->pluck('name')->all();

        $this->get('/brgys/create')
            ->assertOk()
            ->assertSee('name="id"', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="municipality"', false)
            ->assertSee('name="population"', false)
            ->assertSee('action="'.route('brgys.store').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('value="'.$municipality->name.'"', false)
            ->assertSee('>'.$municipality->name.'</option>', false)
            ->assertSee('>Virac</option>', false)
            ->assertSee('>San Andres</option>', false)
            ->assertSee('>Bato</option>', false);

        $this->assertContains('Virac', $municipalityNames);
        $this->assertContains('San Andres', $municipalityNames);
        $this->assertContains('Bato', $municipalityNames);
    }

    public function test_invalid_barangay_submission_does_not_save(): void
    {
        $this->from('/brgys/create')
            ->post('/brgys', [
                'id' => 7,
                'name' => 'New Barangay',
                'municipality' => 'Virac',
                'population' => 'not-a-number',
            ])
            ->assertRedirect('/brgys/create')
            ->assertSessionHasErrors('population');

        $this->assertSame(
            $this->originalBarangays,
            file_get_contents(storage_path('app/private/barangays.json'))
        );

        $this->get('/brgys/create')
            ->assertOk()
            ->assertSee('value="New Barangay"', false)
            ->assertSee('value="Virac" selected', false);
    }

    public function test_valid_barangay_submission_redirects_and_saves_all_fields(): void
    {
        $this->post('/brgys', [
            'id' => 7,
            'name' => 'New Barangay',
            'municipality' => 'Virac',
            'population' => 3000,
        ])
            ->assertRedirect(route('brgys.index'))
            ->assertSessionHas('success', 'Barangay added successfully.');

        $this->get('/brgys')
            ->assertOk()
            ->assertSee('New Barangay');

        $this->get('/brgys/7')
            ->assertOk()
            ->assertSee('New Barangay')
            ->assertSee('3000')
            ->assertDontSee('Barangay added successfully.');

        $savedBarangays = json_decode(
            file_get_contents(storage_path('app/private/barangays.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $this->assertCount(7, $savedBarangays);
        $this->assertSame(1, count(array_filter(
            $savedBarangays,
            fn (array $brgy) => $brgy['id'] === 7
        )));
    }
}
