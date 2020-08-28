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
        
        info($csv);
        dd();
        dump($csv[0]);
        for($i = 1; $i< count($csv); $i++)
        {
            if(strpos($csv[$i][2], '> TOTAL') !== false) {
                continue;
            }
            dump($csv[$i]);
            $manager = Manager::firstOrCreate([
                'manager_id' => $csv[$i][4] ?? NULL,
                'full_name' => $csv[$i][6] ?? NULL
            ]);

            Customer::firstOrCreate([
                'manager_id' => $manager->id,
                'name' => $csv[$i][7] ?? NULL,
                'region' => $csv[$i][10] ?? NULL,
                'order_excel_id' => $this->excel->id
            ]);

            if($order->id !== 'Null') {
                $orderId = $order->id;
            }
            dump($csv[$i]);
            Payment::create([
                'order_excel_id' => $orderId,
                'seq' => $csv[$i][12] ?? NULL,
                'amount' => $csv[$i][1] ?? NULL,
                'remain' => $csv[$i][11] ?? NULL,
                'in_charge' => $csv[$i][5] ?? NULL,
                'contract_no' => $csv[$i][2] ?? NULL,
                'deadline' =>$csv[$i][0] !== '0' ? date('Y-m-d H:i:s', substr($csv[$i][0],0,strlen($csv[$i][0])-3)) : NULL,
                'payment_date' =>$csv[$i][9] !== '' ? date('Y-m-d H:i:s', substr($csv[$i][9],0,strlen($csv[$i][9])-3)) : NULL,
                'paid' => $csv[$i][8] ?? NULL
            ]);
        }
    }
}
