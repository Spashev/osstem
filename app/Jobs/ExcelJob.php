<?php

namespace App\Jobs;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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
        $region_id = NULL;
        $updated_item = [];

        $payments_hash = Payment::all();
        foreach ($records as $record) {
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

                if ($region_id !== $record['REGION']) {
                    $customer = Region::firstOrCreate([
                        'name' => $record['REGION'],
                        'region_id' => $record['ID REGION']
                    ]);
                    $region_id = $record['REGION'];
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
                    'manager_id' => $manager->id,
                    'customer_id' => $customer->id,
                    'hash' => '$_' . $record['CONTRACT NO'] . '_S_' . $record['SEQ'],
                    'seq' => $record['SEQ'],
                    'amount' => $record['AMOUNT'],
                    'remain' => $record['REMAIN'],
                    'deadline' => $record['DEADLINE'] == '0000/00/00' ? NULL : Carbon::parse($record['DEADLINE'])->format('Y-m-d H:i:s'),
                    'payment_date' => $record['PAYMENT DATE'] == '0000/00/00' ? NULL : Carbon::parse($record['PAYMENT DATE'])->format('Y-m-d H:i:s'),
                    'paid' => $record['PAID']
                ]);
            } else {
                foreach ($hash_p as $item) {
                    if ($item->paid !== $record['PAID']) {
                        $item->paid = $record['PAID'];
                        $item->remain = $record['REMAIN'];
                        $item->current_payment_day = Carbon::now()->format('Y-m-d H:i:s');
                        $item->save();
                        $updated_item[] = $item->toArray();
                    }
                }
            }
        }
        if (count($updated_item) > 0) {
            $fileName = '/update_payment.csv';
            $columns = [
                'CUSTOMER',
                'REGION ID',
                'REGION',
                'IN-CHARGE',
                'MANAGER',
                'CONTRACT NO',
                'SEQ',
                'DEADLINE',
                'AMOUNT',
                'PAID',
                'REMAIN',
                'PERCENT',
                'AMOUNT PERCENT'
            ];
            $path = public_path('storage/upload' . $fileName);
            $file = fopen($path, 'w+');
            fputcsv($file, $columns);
            foreach ($updated_item as $item) {
                $row['CUSTOMER']  = $item->contract->customer->name;
                $row['REGION ID']    = $item->contract->customer->region_id;
                $row['REGION']    = $item->contract->customer->region;
                $row['IN-CHARGE']  = $item->contract->manager->in_charge;
                $row['MANAGER']  = $item->contract->manager->name;
                $row['CONTRACT NO']  = $item->contract->contract_no;
                $row['SEQ']  = $item->seq;
                $row['DEADLINE']  = $item->deadline;
                $row['AMOUNT']  = $item->amount;
                $row['PAID']  = $item->paid;
                $row['REMAIN']  = $item->remain;
                $row['PERCENT']  = $item->percent;
                $row['AMOUNT PERCENT']  = $item->amount_percent;
                fputcsv($file, [
                    $row['CUSTOMER'],
                    $row['REGION ID'],
                    $row['REGION'],
                    $row['IN-CHARGE'],
                    $row['MANAGER'],
                    $row['CONTRACT NO'],
                    $row['SEQ'],
                    $row['DEADLINE'],
                    $row['AMOUNT'],
                    $row['PAID'],
                    $row['REMAIN'],
                    $row['PERCENT'],
                    $row['AMOUNT PERCENT']
                ]);
            }
            fclose($file);
            return $updated_item;
        }
    }
}