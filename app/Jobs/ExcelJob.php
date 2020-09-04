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
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        $manager_id = NULL;
        $customer_id = NULL;
        foreach ($records as $offset => $record) {
            dump($record);
            if (strpos($record['CONTRACT NO'], '> TOTAL') !== false) {
                continue;
            }

            $manager = Manager::updateOrCreate([
                'name' => $record['MANAGER'],
                'in_charge' => $record['IN-CHARGE']
            ]);

            if ($manager->id !== 'Null') {
                $manager_id = $manager->id;
            }

            $customer = Customer::firstOrCreate([
                'manager_id' => $manager_id,
                'customer_id' => $record['CUSTOMER'],
                'name' => $record['NAME'],
                'region' => $record['REGION'],
                'region_id' => $record['ID REGION']
            ]);

            if ($customer->id !== 'Null') {
                $customer_id = $customer->id;
            }

            Payment::create([
                'customer_id' => $customer_id,
                'manager_id' => $manager_id,
                'seq' => $record['SEQ'],
                'amount' => $record['AMOUNT'],
                'remain' => $record['REMAIN'],
                'contract_no' => $record['CONTRACT NO'],
                'deadline' => $record['DEADLINE'] == '0000/00/00' ? NULL : Carbon::parse($record['DEADLINE'])->format('Y-m-d H:i:s'),
                'payment_date' => $record['PAYMENT DATE'] == '0000/00/00' ? NULL : Carbon::parse($record['PAYMENT DATE'])->format('Y-m-d H:i:s'),
                'paid' => $record['PAID']
            ]);
        }
    }
}