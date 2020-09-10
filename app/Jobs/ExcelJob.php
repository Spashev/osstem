<?php

namespace App\Jobs;

use App\Models\Contract;
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

        $customer_id = $contract_no = NULL;
        $in_charge = $customer_id = NULL;
        $updated_item = [];
        
        $payments_hash = Payment::all();
        foreach ($records as $record) {
            dump($record);
            $hash = '$_' . $record['CONTRACT NO'] . '_S_' . $record['SEQ'];
            $hash_p = $payments_hash->filter(function ($value, $key) use ($hash) {
                return $value->hash == $hash;
            });
            if (count($hash_p) == 0) {
                if (strpos($record['CONTRACT NO'], '> TOTAL') !== false) {
                    continue;
                }

                if ($in_charge !== $record['IN-CHARGE']) {
                    $manager = Manager::updateOrCreate([
                        'name' => $record['MANAGER'],
                        'in_charge' => $record['IN-CHARGE']
                    ]);
                    $in_charge = $record['IN-CHARGE'];
                }

                if ($customer_id !== $record['CUSTOMER']) {
                    $customer = Customer::firstOrCreate([
                        'manager_id' => $manager->id,
                        'customer_id' => $record['CUSTOMER'],
                        'name' => $record['NAME'],
                        'region' => $record['REGION'],
                        'region_id' => $record['ID REGION']
                    ]);
                    $customer_id = $record['CUSTOMER'];
                }

                if ($contract_no !== $record['CONTRACT NO']) {
                    $contract = Contract::firstOrCreate([
                        'customer_id' => $customer->id,
                        'manager_id' => $manager->id,
                        'contract_no' => $record['CONTRACT NO']
                    ]);
                }


                Payment::create([
                    'contract_id' => $contract->id,
                    'hash' => '$_' . $record['CONTRACT NO'] . '_S_' . $record['SEQ'],
                    'seq' => $record['SEQ'],
                    'amount' => $record['AMOUNT'],
                    'remain' => $record['REMAIN'],
                    'deadline' => $record['DEADLINE'] == '0000/00/00' ? NULL : Carbon::parse($record['DEADLINE'])->format('Y-m-d H:i:s'),
                    'payment_date' => $record['PAYMENT DATE'] == '0000/00/00' ? NULL : Carbon::parse($record['PAYMENT DATE'])->format('Y-m-d H:i:s'),
                    'paid' => $record['PAID']
                ]);
            }
            foreach ($hash_p as $item) {
                if ($item->paid !== $record['PAID']) {
                    $item->paid = $record['PAID'];
                    $item->remain = $record['REMAIN'];
                    $item->current_payment_day = Carbon::now()->format('Y-m-d H:i:s');
                    $item->save();
                    $updated_item[] = $item;
                }
            }
        }
        dump('Result: ', $updated_item);
        if (count($updated_item) > 0) {
            return $updated_item;
        } else {
            return  'Excel file uploaded';
        }
    }
}