<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Manager;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use League\Csv\Writer;

class ExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
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
        $csv = iterator_to_array($reader->getRecords());

        dump($csv[0]);
        $manager_id = NULL;
        $customer_id = NULL;
        for($i = 1; $i< count($csv); $i++)
        {
            dump($csv[$i]);
            if(strpos($csv[$i][6], '> TOTAL') !== false) {
                continue;
            }

            $manager = Manager::updateOrCreate([
                'name' => $csv[$i][5],
                'in_charge' => $csv[$i][4]
            ]);

            if($manager->id !== 'Null') {
                $manager_id = $manager->id;
            }

            $customer = Customer::firstOrCreate([
                'manager_id' => $manager_id,
                'customer_id' => $csv[$i][0],
                'name' => $csv[$i][1],
                'region' => $csv[$i][3],
                'region_id' => $csv[$i][2]
            ]);

            if($customer->id !== 'Null') {
                $customer_id = $customer->id;
            }

            Payment::create([
                'customer_id' => $customer_id,
                'manager_id' => $manager_id,
                'seq' => $csv[$i][7],
                'amount' => $csv[$i][9],
                'remain' => $csv[$i][12],
                'contract_no' => $csv[$i][6],
                'deadline' => $csv[$i][8] =='0000/00/00' ? NULL : Carbon::parse($csv[$i][8])->format('Y-m-d H:i:s'),
                'payment_date' => $csv[$i][10] =='0000/00/00' ? NULL : Carbon::parse($csv[$i][10])->format('Y-m-d H:i:s') ,
                'paid' => $csv[$i][11]
            ]);
        }
    }
}
