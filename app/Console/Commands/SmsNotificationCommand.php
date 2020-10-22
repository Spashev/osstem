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
        $to = Carbon::now()->addDays('3')->format('Y-m-d');
        $customers = Customer::whereHas('payments', function ($q) use ($to) {
            $q->where('sms_status', 'on')->where('remain', '>', 0)->where('deadline', $to);
        })->get();
        foreach ($customers as $customer) {
            $payments = $customer->notifyPayments;
            $payments = $payments->filter(function ($value, $key) {
                return $value->remain > 0 and $value->sms_status == 'on';
            });
            if (count($payments) > 0 and (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
                dump('За 3 дня оплаты смс');
                $sum = 0;
                $to_sum = 0;
                $start = new Carbon('first day of this month');
                $end = new Carbon('last day of this month');
                foreach ($payments as $payment) {
                    $payment_percent = Payment::where('contract_id', $payment->contract_id)->where('remain', '>', 0)->where('deadline', '<=', $to)->get();
                    if ($payment_percent->count() == 1) {
                        $sum = $payment_percent->first()->remain;
                    } else {
                        foreach ($payment_percent as $payment) {
                            if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                                $sum += $payment->remain;
                            } else {
                                $delayInDays = Carbon::createFromDate($payment->deadline)->addMonth()->diffInDays($payment->deadline);
                                if ($payment->percent == 0) {
                                    $sum += $payment->remain;
                                } else {
                                    $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays + $payment->remain;
                                }
                            }
                        }
                        $sum += $payment_percent->last()->remian;
                    }
                    // dump($sum, $payment->deadline, $payment->hash);
                }
                if ($sum != 0) {
                    $message = "Уважаемый %s!,\n Просим оплатить %sтг. в срок %s.";
                    $result = [
                        'customer_name' => $customer->name,
                        'customer_phone' => $customer->phone,
                        'amount' => $sum,
                        'deadline' => Str::substr($to, 0, 10),
                    ];
                    $text = sprintf($message, $result['customer_name'], $result['amount'], $result['deadline']);
                    dump($text);
                    // $sms = new SmsService();
                    // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                    // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                    $status = true;
                    if ($status) {
                        $customer->notifications()->create([
                            'customer_id' => $customer->id,
                            'customer_name' => $result['customer_name'],
                            'contract_no' => $sum,
                            'phone_number' => $result['customer_phone'],
                            'amount' => $result['amount'],
                            'amount_percent' => 0,
                            'status' => 0
                        ]);
                        $customer->save();
                    }
                }
            }
        }

        $now = Carbon::now()->format('Y-m-d');
        $customers = Customer::whereHas('payments', function ($q) use ($now) {
            $q->where('deadline', '<', $now)->where('sms_status', 'on')->where('remain', '>', 0);
        })->get();
        foreach ($customers as $customer) {
            $payments = $customer->deadlinePayments;
            $payments = $payments->filter(function ($value, $key) {
                return $value->remain > 0 and $value->sms_status == 'on';
            });

            if (count($payments) > 0 and (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
                dump('Пения');
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
                        $delayInDays = Carbon::createFromDate($payment->deadline)->addMonth()->diffInDays($payment->deadline);
                        $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                        $total_remian += $payment->remain;
                    }
                    // dump($sum, $delayInDays, $payment->remain, $payment->deadline);
                }
                if ($sum != 0) {
                    $message = "Уважаемый %s!\nЗадолжность на %s\nДолг: %s\nПеня: %s";
                    $result = [
                        'customer_name' => $customer->name,
                        'customer_phone' => $customer->phone,
                        'amount' => $sum,
                        'total_remain' => $total_remian,
                        'deadline' => Str::substr($payment->deadline, 0, 10),
                    ];
                    $text = sprintf($message, $result['customer_name'], $now, $result['total_remain'], $result['amount']);
                    dump($text);
                    // $sms = new SmsService();
                    // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                    // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                    $status = true;
                    if ($status) {
                        $customer->notifications()->create([
                            'customer_id' => $customer->id,
                            'customer_name' => $result['customer_name'],
                            'contract_no' => $sum,
                            'phone_number' => $result['customer_phone'],
                            'amount' => $result['total_remain'],
                            'amount_percent' => $result['amount'],
                            'status' => 1
                        ]);
                        $customer->save();
                    }
                }
            }
        }
        return 0;
    }
}