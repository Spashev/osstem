<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use App\Models\Customer;

class ImportJob implements ShouldQueue
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
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        dump($records);
        foreach ($records as $record) {
            $nomer = $record['CELL'] ? $record['CELL'] : $record['TEL NO'];
            $customer = Customer::where('customer_id', $record['CODE'])->first();
            if ($customer) {
                $customer->phone = $nomer;
                $customer->city = $record['(Bill To)']; #новое название
                $customer->district = $record['district'];
                $customer->address = $record['address'];
                $customer->save();
            }
        }
        return 'All customer contacts saved successfully';
    }
}