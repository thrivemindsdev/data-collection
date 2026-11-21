<?php

namespace Tests\Feature;

use App\Models\Bhakt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BhaktSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed standard admin user
        User::create([
            'name' => 'Admin - Shri Anandam Dham',
            'email' => 'admin@ashram.in',
            'password' => bcrypt('Admin@1234'),
            'role' => 'admin',
        ]);
    }

    public function test_registration_requires_valid_10_digit_numeric_mobile(): void
    {
        // Missing mobile
        $response = $this->post('/register', [
            'name' => 'Test Devotee',
            'mobile' => '',
        ]);
        $response->assertSessionHasErrors('mobile');

        // Non-numeric mobile
        $response = $this->post('/register', [
            'name' => 'Test Devotee',
            'mobile' => '98765abcde',
        ]);
        $response->assertSessionHasErrors('mobile');

        // Less than 10 digits
        $response = $this->post('/register', [
            'name' => 'Test Devotee',
            'mobile' => '98765',
        ]);
        $response->assertSessionHasErrors('mobile');

        // Valid 10 digits
        $response = $this->post('/register', [
            'name' => 'Radheshyam Sharma',
            'mobile' => '9876543210',
            'state' => 'Chhattisgarh',
            'branch' => 'Shivreinarayan',
            'consent' => '1',
        ]);
        $response->assertRedirect();
        
        $bhakt = Bhakt::where('mobile', '9876543210')->first();
        $this->assertNotNull($bhakt);
        $this->assertStringStartsWith('SAD-REG-', $bhakt->registration_number);
    }

    public function test_application_status_displays_exact_registration_number_with_no_discrepancy(): void
    {
        $bhakt = Bhakt::create([
            'name' => 'Shiva Kumar',
            'mobile' => '8714553793',
            'registration_number' => 'SAD-REG-2611-39487',
            'bhakt_id' => 'SAD-SN26-XBGSL',
            'status' => 'approved',
            'branch' => 'Shivreinarayan',
            'city' => 'Kochi',
            'state' => 'Kerala',
        ]);

        $response = $this->get('/status?reg_id=' . $bhakt->registration_number . '&mobile=' . $bhakt->mobile);
        $response->assertStatus(200);

        // Verify the response contains the exact registration number and NOT 'REG-000008'
        $response->assertSee('SAD-REG-2611-39487');
        $response->assertDontSee('REG-000008');
        $response->assertSee('SAD-SN26-XBGSL');
    }

    public function test_bilingual_display_translates_between_english_and_hindi(): void
    {
        $bhakt = Bhakt::create([
            'name' => 'राधेश्याम शर्मा',
            'mobile' => '9800000001',
            'registration_number' => 'SAD-REG-2611-00001',
            'bhakt_id' => 'SAD-DK26-12345',
            'status' => 'approved',
            'branch' => 'Shivreinarayan',
            'district' => 'Raipur',
            'state' => 'Chhattisgarh',
            'diksha_taken' => true,
        ]);

        // Login as admin
        $admin = User::first();
        $this->actingAs($admin);

        // English view
        $responseEn = $this->get('/admin/bhakts?lang=en');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Radheshyam Sharma');
        $responseEn->assertSee('Shivreinarayan');
        $responseEn->assertSee('Chhattisgarh');

        // Hindi view
        $responseHi = $this->get('/admin/bhakts?lang=hi');
        $responseHi->assertStatus(200);
        $responseHi->assertSee('राधेश्याम शर्मा');
        $responseHi->assertSee('शिवरीनारायण');
        $responseHi->assertSee('छत्तीसगढ़');
    }

    public function test_admin_can_delete_bhakt_record(): void
    {
        $bhakt = Bhakt::create([
            'name' => 'To Delete Devotee',
            'mobile' => '9998887776',
            'registration_number' => 'SAD-REG-2611-99999',
            'status' => 'pending',
            'branch' => 'Shivreinarayan',
        ]);

        $admin = User::first();
        $this->actingAs($admin);

        // Delete devotee
        $response = $this->delete('/admin/bhakts/' . $bhakt->id);
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('bhakts', [
            'id' => $bhakt->id,
        ]);
    }

    public function test_branch_selection_and_custom_other_branch(): void
    {
        // 1. Standard new branch: Raipur
        $response = $this->post('/register', [
            'name' => 'Devotee Raipur',
            'mobile' => '9822334455',
            'branch' => 'Raipur',
            'consent' => '1',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('bhakts', [
            'mobile' => '9822334455',
            'branch' => 'Raipur',
        ]);

        // 2. Custom "Other" branch specifying custom name
        $responseOther = $this->post('/register', [
            'name' => 'Devotee Custom',
            'mobile' => '9811223344',
            'branch' => 'Other',
            'other_branch' => 'Bhilai Seva Kendra',
            'consent' => '1',
        ]);
        $responseOther->assertRedirect();
        $this->assertDatabaseHas('bhakts', [
            'mobile' => '9811223344',
            'branch' => 'Bhilai Seva Kendra',
        ]);
    }

    public function test_branch_specific_bhakt_id_generation(): void
    {
        $bRaipur = new Bhakt(['branch' => 'Raipur', 'diksha_taken' => false]);
        $this->assertStringStartsWith('SAD-RP', Bhakt::generateBhaktId($bRaipur));

        $bChampa = new Bhakt(['branch' => 'Champa', 'diksha_taken' => false]);
        $this->assertStringStartsWith('SAD-CH', Bhakt::generateBhaktId($bChampa));

        $bJanjgir = new Bhakt(['branch' => 'Janjgir', 'diksha_taken' => false]);
        $this->assertStringStartsWith('SAD-JG', Bhakt::generateBhaktId($bJanjgir));

        $bVaranasi = new Bhakt(['branch' => 'Varanasi', 'diksha_taken' => false]);
        $this->assertStringStartsWith('SAD-VN', Bhakt::generateBhaktId($bVaranasi));
    }
}
