<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Vendor;
use App\Models\Service;
use Illuminate\Database\Seeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@logistics.com',
        ]);
        $this->command->info('Admin user created.');
        $mb=\App\Models\Brand::factory()->create([
            'name' => 'Mercedes-Benz',
            'code' => 'mb',
            'manufacturer' => 'The Mercedes-Benz Group AG',
        ]);
        $this->command->info('Mercedes-benz Brand created.');
        $mz=\App\Models\Brand::factory()->create([
            'name' => 'Mazda',
            'code' => 'mz',
            'manufacturer' => 'The Mazda Motor Corporation',
        ]);
        $this->command->info('Mazda Brand created.');
        $mz2=\App\Models\Modell::factory()->create([
            'brand_id' => $mz,
            'name' => 'MAZDA-2',
            'code' => 'mz2sl',
            'default_group'=>'saloon',
            'default_engine_power'=>1498
        ]);
        $this->command->info('MZ Model MAZDA2 created.');
        \App\Models\Modell::factory()->create([
            'brand_id' => $mz,
            'name' => 'MAZDA-3',
            'code' => 'mz3sl',
            'default_group'=>'saloon',
            'default_engine_power'=>1498
        ]);
        $this->command->info('MZ Model MAZDA-3 created.');
        \App\Models\Modell::factory()->create([
            'brand_id' => $mz,
            'name' => 'CX-5',
            'code' => 'cx5wg',
            'default_group'=>'wagon',
            'default_engine_power'=>1998
        ]);
        $this->command->info('MZ Model CX-5 created.');

        \App\Models\Modell::factory()->create([
            'brand_id' => $mb,
            'name' => 'A-200',
            'code' => 'a200sl',
            'default_group'=>'saloon',
            'default_engine_power'=>1332
        ]);
        $this->command->info('MB Model A-Class created.');
        
        \App\Models\Modell::factory()->create([
            'brand_id' => $mb,
            'name' => 'E-200',
            'code' => 'e200sl',
            'default_group'=>'saloon',
            'default_engine_power'=>1991
        ]);
        $this->command->info('MB Model E-200 created.');

        \App\Models\Modell::factory()->create([
            'brand_id' => $mb,
            'name' => 'A-300',
            'code' => 'a300sl',
            'default_group'=>'saloon',
            'default_engine_power'=>1332
        ]);
        $this->command->info('MB Model A-300 created.');

        \App\Models\Modell::factory()->create([
            'brand_id' => $mb,
            'name' => 'GLB-200',
            'code' => 'glb200wg',
            'default_group'=>'wagon',
            'default_engine_power'=>1332
        ]);
        $this->command->info('MB Model GLB-200 created.');

        $il=\App\Models\Service::factory()->create([
            'name'=>'Import License',
            'code'=>'il',
            'Description'=>'Import License application service'
        ]);
        $this->command->info('Import License application service is created.');
        
        \App\Models\Service::factory()->create([
            'name'=>'Customs Clearance',
            'code'=>'customs',
            'Description'=>'Customs Clearance service'
        ]);
        $this->command->info('Customs Clearance service is created.');

        \App\Models\Service::factory()->create([
            'name'=>'Forwarding',
            'code'=>'forwarding',
            'Description'=>'Forwarding service'
        ]);
        $this->command->info('Forwarding service is created.');

        \App\Models\Service::factory()->create([
            'name'=>'Insurance',
            'code'=>'insurance',
            'Description'=>'Insurance service'
        ]);
        $this->command->info('Insurance service is created.');

        \App\Models\Service::factory()->create([
            'name'=>'Transportation',
            'code'=>'transport',
            'Description'=>'Local transportation service'
        ]);
        $this->command->info('Local transportation service is created.');

        \App\Models\Service::factory()->create([
            'name'=>'Warehouseing',
            'code'=>'warehouse',
            'Description'=>'Warehouseing service'
        ]);
        $this->command->info('Warehouseing service is created.');
        \App\Models\Service::factory()->create([
            'name'=>'Vehicle Registration',
            'code'=>'rta',
            'Description'=>'Road Transport Administration service'
        ]);
        $this->command->info('Vehicle Registration is created.');
        
        $sgPort=\App\Models\Location::factory()->create([
            'name'=>'Singapore Port',
            'code'=>'sgp',
            'country'=>'Singapore',
            'city'=>'Singapore',
            'type'=>'port'
        ]);        
        $this->command->info('Singpare Port is created.');
        
        $mmPort=\App\Models\Location::factory()->create([
            'name'=>'Myanmar Port',
            'code'=>'mmp',
            'country'=>'Myanmar',
            'city'=>'Yangon',
            'type'=>'port'
        ]);        
        $this->command->info('Myanmar Port is created.');
        
        
       $dps= \App\Models\Location::factory()->create([
            'name'=>'DPS warehouse',
            'code'=>'dps',
            'country'=>'Singapore',
            'city'=>'Singapore',
            'type'=>'warehouse'
        ]);
        $this->command->info('Location DPS is created.');
       $ecl= \App\Models\Location::factory()->create([
            'name'=>'ECL warehouse',
            'code'=>'ecl',
            'country'=>'Myanmar',
            'city'=>'Yangon',
            'type'=>'warehouse'
        ]);
        $this->command->info('Location ECL is created.');
        \App\Models\Location::factory()->create([
            'name'=>'MB Showroom',
            'code'=>'mb3s',
            'country'=>'Myanmar',
            'city'=>'Yangon',
            'type'=>'shawroom'
        ]);
        $this->command->info('MB Showroom is created.');
        \App\Models\Location::factory()->create([
            'name'=>'MZ Showroom',
            'code'=>'mz3s',
            'country'=>'Myanmar',
            'city'=>'Yangon',
            'type'=>'shawroom'
        ]);
        $this->command->info('MZ Showroom is created.');
        \App\Models\Location::factory()->create([
            'name'=>'Delivered',
            'code'=>'delivered',
            'country'=>'Myanmar',
            'city'=>'Yangon',
            'type'=>'delivered'
        ]);
        $this->command->info('Delivered is created.');

        \App\Models\Stock::factory()->create([
            'number'=>'1001',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DL2SAAHW252726',
            'country'=>'Thailand',
            'cif_price'=>300.50,
            'location_id'=>$dps
        ]);
        $this->command->info('MZ vin created.');

        \App\Models\Stock::factory()->create([
            'number'=>'1002',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DL2SAAHW249447',
            'country'=>'Thailand',
            'cif_price'=>350.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MZ2 vin created.');
        \App\Models\Stock::factory()->create([
            'number'=>'1003',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DL2SAAHW249984',
            'country'=>'Thailand',
            'cif_price'=>300.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MZ2 vin created.');
        
        \App\Models\Stock::factory()->create([
            'number'=>'1004',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DJ2HAAHW253122',
            'country'=>'Thailand',
            'cif_price'=>302.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MZ2 vin created.');
        \App\Models\Stock::factory()->create([
            'number'=>'1005',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DL2SAAHW252699',
            'country'=>'Thailand',
            'cif_price'=>330.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MZ2 vin created.');
        \App\Models\Stock::factory()->create([
            'number'=>'1006',
            'brand_id' => $mz,
            'modell_id' => $mz2,
            'engine_power' => '1498',
            'model_year' => '2017',
            'vin' => 'MM7DL2SAAHW252728',
            'country'=>'Thailand',
            'cif_price'=>500.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MZ2 vin created.');
        
        \App\Models\Stock::factory()->create([
            'number'=>'1007',
            'brand_id' => $mb,
            'modell_id' => 5,
            'engine_power' => '1991',
            'model_year' => '2017',
            'group'=>'wagon',
            'vin' => 'WDD2130421A223034',
            'country'=>'Germany',
            'cif_price'=>3000.50,
            'location_id'=>$dps

        ]);
        $this->command->info('MB vin created.');

        \App\Models\Vendor::factory()->create([
            'name'=>'Fujitrans Myanmar Co.,Ltd',
            'contact_person'=>'Mr. Myat Thu',
            'phone'=>'09-1234567',
            'email'=>'myat@fujitrans.com',
            'appointed_at'=>now()->subYear(3)
        ]);
        $this->command->info('Vendor: Fujitrans Myanmar is created.');
        \App\Models\Vendor::factory()->create([
            'name'=>'Innovo Co.,Ltd',
            'contact_person'=>'Mr. Aung Myat Thu',
            'phone'=>'09-7654321',
            'email'=>'aung.myat@innovo.com',
            'appointed_at'=>now()->subYear(2)
        ]);
        $this->command->info('Vendor: Innovo is created.');
        \App\Models\Vendor::factory()->create([
            'name'=>'ECL Myanmar Co.,Ltd',
            'contact_person'=>'Mr. Sai',
            'phone'=>'09-99887766',
            'email'=>'sai@eclmyanmar.com',
            'appointed_at'=>now()->subYear(2)
        ]);
        $this->command->info('Vendor: ECL Myanmar is created.');
        \App\Models\Vendor::factory()->create([
            'name'=>'Bravel Co.,Ltd',
            'contact_person'=>'Mr. Wunna',
            'phone'=>'09-777888999',
            'email'=>'wunna@bravely.com',
            'appointed_at'=>now()->subYear(2),
            'terminated_at'=>now()->subMonth(2)
        ]);
        $this->command->info('Vendor: Bravely is created.');

        $services=Service::all();
        Vendor::all()->each(function($vendor) use ($services){
            $vendor->services()->attach(
                $services->random(rand(3, 6))->pluck('id')->toArray()
            );
        });
        $this->command->info('Vendor service attached complete.');

        
        \App\Models\Import::factory()->create([     
            'note'=>'Urgent request'
        ]);
        $this->command->info('Import is created.');
  
  
        \App\Models\Customs::factory()->create([
            'vendor_id'=>1,
            'ro_number'=>'100012300',
            'started_at'=>now()->subDay(9),
            'ro_date'=>now()->subDay(3),
            'currency'=>'USD',
            'total_taxes'=>345658
        ]);
        $this->command->info('Releaser Order is created.');

        \App\Models\Schedule::factory()->create([
            'vendor_id'=>1,
            'name'=>'Malaysia Star',
            'voy'=>'45',
            'etd'=>now()->subMonth(1),
            'eta'=>now()->subDay(12),
            'pol_id'=>$sgPort,
            'pod_id'=>$mmPort,
        ]);
        $this->command->info('Schedule is created.');

        \App\Models\ImportLicense::factory()->create([
            'vendor_id'=>2,
            'number'=>'ILV 21-22 00231',
            'received_at'=>now()->subMonth(2),
            'expired_at'=>now()->subDay(1),            
        ]);

        $this->command->info('Import License is created.');

    }
}
 