<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Smsc\SmsService;

class SmsNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command start notification service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $customers = Customer::whereHas('payments', function ($q) use ($start, $end) {
            $q->where('sms_status', 'on')->where('remain', '>', 0)->whereBetween('deadline', [$start, $end]);
        })->get();
        foreach ($customers as $customer) {
            if ($customer->sms_status == 'on' and $customer->notifications->count() == 0) {
                $payments = $customer->payments->where('remain', '>', 0)->whereBetween('deadline', [$start, $end]);
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if ($payments->count() > 0) {
                    $sum = 0;
                    $total_remian = 0;
                    foreach ($payments as $payment) {
                        $start = new Carbon('first day of this month');
                        $end = new Carbon('last day of this month');
                        if ($payment->first()->percent == 0) {
                            $sum += $payment->remain;
                            $total_remian = 0;
                        } else if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        } else {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        }
                        dump($delayInDays, $total_remian);
                    }
                    dump($sum);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum + $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['amount'], $result['deadline']);
                        info($text);
                        dump($text);
                        // $sms = new SmsService();
                        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                        // $status = true;
                        // if ($status) {
                        //     $customer->notifications()->create([
                        //         'customer_id' => $customer->id,
                        //         'payment_id' => $payment->id,
                        //         'customer_name' => $result['customer_name'],
                        //         'contract_no' => $payment->contract->contract_no,
                        //         'phone_number' => $result['customer_phone'],
                        //         'amount' => $result['amount'],
                        //         'amount_percent' => 0,
                        //         'status' => 0
                        //     ]);
                        //     $customer->save();
                        // }
                    }
                }
            } else if ($customer->sms_status == 'on' and $customer->notifications->count() > 0) {
                $notify_flag = true;
                foreach ($customer->notifications as $notify) {
                    if ($notify->created_at->between($start, $end) and $notify->status == 0) {
                        $notify_flag = false;
                        break;
                    }
                }
                if ($notify_flag) {
                    $total_remian = 0;
                    $payments = $customer->deadlinePayments;
                    $payments = $payments->filter(function ($value, $key) {
                        return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                    });
                    $sum = 0;
                    foreach ($payments as $payment) {
                        $start = new Carbon('first day of this month');
                        $end = new Carbon('last day of this month');
                        if ($payment->first()->percent == 0) {
                            $sum += $payment->remain;
                            $total_remian = 0;
                        } else if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        } else {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        }
                        dump($delayInDays, $total_remian);
                    }
                    dump($sum);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum + $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['amount'], $result['deadline']);
                        info($text);
                        dump($text);
                        // $sms = new SmsService();
                        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                        // $status = true;
                        // if ($status) {
                        //     $customer->notifications()->create([
                        //         'customer_id' => $customer->id,
                        //         'payment_id' => $payment->id,
                        //         'customer_name' => $result['customer_name'],
                        //         'contract_no' => $payment->contract->contract_no,
                        //         'phone_number' => $result['customer_phone'],
                        //         'amount' => $result['amount'],
                        //         'amount_percent' => 0,
                        //         'status' => 0
                        //     ]);
                        //     $customer->save();
                        // }
                    }
                }
            }
        }
        return 0;
    }
}