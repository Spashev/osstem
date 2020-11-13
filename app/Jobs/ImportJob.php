<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use App\Models\Customer;
use App\Models\Region;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $upload_record_total = 0;
    protected $excel;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($excel)
    {
        $this->excel = $excel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inputFileName = storage_path('app/public/' . $this->excel->path);
        $reader = Reader::createFromPath($inputFileName, 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $this->upload_record_total = collect($records)->count();
        $f = fopen(public_path("upload_file/upload_client.txt"), 'w');
        fclose($f);
        file_put_contents(public_path("upload_file/upload_client.txt"), "total " . $this->upload_record_total .", current 1");
        
        foreach ($records as $key => $record) {
            if($key != '') {
                $format = "total %d, current %d";
                $text = sprintf($format, $this->upload_record_total, $key);
                file_put_contents(public_path('upload_file/upload_client.txt'), $text);
            }
            $nomer = $record['CELL'] ? $record['CELL'] : $record['TEL NO'];
            $customer = Customer::where('customer_id', $record['CODE'])->first();
            if ($customer) {
                info($customer);
                $customer->phone = $nomer;
                $customer->city = $record['(Bill To)']; #новое название
                $customer->district = $record['district'];
                $customer->address = $record['address'];
                $customer->save();
            } else {
                $manager = Manager::where('in_charge', $record['IN-CHARGE'])->first();
                $region = Region::where('region_id',$record['CITY'])->first();
                
                $customer = Customer::create([
                    'manager_id' => $manager->in_charge,
                    'customer_id' => $record["CODE"],
                    'name' => $record["Doctor's Office"],
                    'region' => $region->name,
                    'region_id' => $record['CITY']
                ]);
            }
        }
        return 'All customer contacts saved successfully';
    }
}