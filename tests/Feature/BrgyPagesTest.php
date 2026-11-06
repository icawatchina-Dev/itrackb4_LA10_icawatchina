<?php

namespace Tests\Feature;

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
            ->assertDontSee('San Isidro Village');
    }

    public function test_create_form_contains_every_barangay_field(): void
    {
        $this->get('/brgys/create')
            ->assertOk()
            ->assertSee('name="id"', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="municipality"', false)
            ->assertSee('name="population"', false)
            ->assertSee('action="'.route('brgys.store').'"', false)
            ->assertSee('name="_token"', false);
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