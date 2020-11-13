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
    public $upload_record_total;

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
        $f = fopen(public_path("upload_file/upload_result.txt"), 'w');
        fclose($f);
        file_put_contents(public_path("upload_file/upload_result.txt"), "total " . $this->upload_record_total .", current 1");
        
        $customer_id = $contract_no = NULL;
        $in_charge = $customer_id = NULL;
        $region_id = NULL;
        $updated_item = [];
        foreach ($records as $key => $record) {
            if($key != '') {
                $format = "total %d, current %d";
                $text = sprintf($format, $this->upload_record_total, $key);
                file_put_contents(public_path('upload_file/upload_result.txt'), $text);
            }

            $hash = $record['CONTRACT NO'] . '_' . $record['SEQ'];
            $hash_p = Payment::where('hash', $hash)->first();
            info($hash_p);
            info($hash);
            if (!$hash_p) {
                if (strpos($record['CONTRACT NO'], '> TOTAL') !== false) {
                    continue;
                }

                if ($in_charge !== $record['IN-CHARGE']) {
                    try {
                        $manager = Manager::updateOrCreate([
                            'in_charge' => $record['IN-CHARGE'],
                            ], [
                            'name' => $record['MANAGER'],
                            'region' => $record['REGION'],
                            'region_id' => $record['ID REGION']
                        ]);
                        $in_charge = $record['IN-CHARGE'];
                    } catch (Exception $e) {
                        print('Error ' . $e->getMessage());
                    }
                }

                if ($region_id !== $record['ID REGION']) {
                        $customer = Region::updateOrCreate([
                            'region_id' => $record['ID REGION']
                        ], ['name' => $record['REGION']]);
                        $region_id = $record['ID REGION'];
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
                    $contract = Contract::updateOrCreate([
                        'contract_no' => $record['CONTRACT NO']
                    ], [
                        'customer_id' => $customer->id,
                        'manager_id' => $manager->id,
                    ]);
                }

                Payment::create([
                    'contract_id' => $contract->id,
                    'manager_id' => $manager->id,
                    'customer_id' => $customer->id,
                    'hash' => $record['CONTRACT NO'] . '_' . $record['SEQ'],
                    'seq' => $record['SEQ'],
                    'amount' => $record['AMOUNT'],
                    'remain' => $record['REMAIN'],
                    'deadline' => $record['DEADLINE'] == '0000/00/00' ? NULL : Carbon::parse($record['DEADLINE'])->format('Y-m-d H:i:s'),
                    'payment_date' => $record['PAYMENT DATE'] == '0000/00/00' ? NULL : Carbon::parse($record['PAYMENT DATE'])->format('Y-m-d H:i:s'),
                    'paid' => $record['PAID']
                ]);
            } else {
                if ($hash_p->paid !== $record['PAID'] && $hash_p->remain != $record['REMAIN']) {
                    $hash_p->paid = $record['PAID'];
                    $hash_p->remain = $record['REMAIN'];
                    $hash_p->payment_date = $record['PAYMENT DATE'] == '0000/00/00' ? NULL : Carbon::parse($record['DEADLINE'])->format('Y-m-d H:i:s');
                    $hash_p->save();
                    $updated_item[] = $hash_p;
                    info($hash_p, $updated_item);
                }
            }
        }
        if (count($updated_item) > 0) {
            $fileName = '/update_payment.csv';
            $f = fopen(public_path("upload_file/" . $fileName), 'w');
            fclose($f);
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
            $path = public_path('upload_file/' . $fileName);
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