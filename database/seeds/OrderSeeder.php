<?php

use Illuminate\Database\Seeder;

use App\Models\UserGroup;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentSeeder::class);
        $this->call(DeliverySeeder::class);

        $payment = Payment::all()->random();
        $delivery = Delivery::all()->random();

        $customers = UserGroup::where('name', 'customer')->first();
        $customer  = $customers->users()->first();

        $orders = factory(Order::class, 10)->create([
            'customer_id' => $customer->id,
            'payment_id'  => $payment->id,
            'delivery_id' => $delivery->id,
        ]);
    }
}
