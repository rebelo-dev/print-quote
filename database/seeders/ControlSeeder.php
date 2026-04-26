<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Material;
use App\Models\PrinterSetting;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash; //not sure if i need this?


class ControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // Super admin (platform admin)
        User::factory()->superAdmin()->create([
            'name'  => 'Super Admin',
            'email' => 'admin@printquote.com',
            'slug'  => 'admin',
        ]);

        // Printer 1
        $printer1 = User::factory()->printer()->create([
            'name'  => 'João Impressões',
            'email' => 'joao@test.com',
            'slug'  => 'joao-impressoes',
        ]);

        $this->seedPrinter($printer1);

        // Printer 2
        $printer2 = User::factory()->printer()->create([
            'name'  => 'Maria Prints',
            'email' => 'maria@test.com',
            'slug'  => 'maria-prints',
        ]);

        $this->seedPrinter($printer2);
    }

    private function seedPrinter(User $printer): void
    {
        // PrinterSettings
        $printer->printerSetting()->create(
            PrinterSetting::factory()->make()->toArray()
        );

        // Materials — 3 by printer
        $materials = Material::factory()->count(3)->make()->toArray();

        foreach ($materials as $index => $materialData) {
            $material = $printer->materials()->create($materialData);

            if ($index === 0) {
                $material->defaulted_at = now();
                $material->save();
            }
        }

        $defaultMaterial = $printer->materials()->whereNotNull('defaulted_at')->first();

        // Customers — 3 by printer
        $customers = Customer::factory()->count(3)->make()->toArray();
        foreach ($customers as $customerData) {
            $printer->customers()->create($customerData);
        }

        $printerCustomers = $printer->customers()->get();

        // Quote Requests — 5 by printer
        foreach (range(1, 5) as $i) {
            $customer = $printerCustomers->random();

            $quoteRequest = $printer->quoteRequests()->create(
                array_merge(
                    QuoteRequest::factory()->make()->toArray(),
                    [
                        'customer_name'  => $customer->name,
                        'customer_email' => $customer->email,
                    ]
                )
            );

            // index customer to quoteRequest
            $quoteRequest->customer_id = $customer->id;
            $quoteRequest->save();

            // requests that become proposals
            if ($i <= 3) {
                $quoteRequest->status = 'quoted';
                $quoteRequest->save();

                $proposalData = QuoteProposal::factory()->make()->toArray();
                $proposal = new QuoteProposal($proposalData);
                $proposal->quote_request_id = $quoteRequest->id;
                $proposal->user_id          = $printer->id;
                $proposal->material_id      = $defaultMaterial->id;
                $proposal->title            = $quoteRequest->title;
                $proposal->save();

                // become jobs when accepted
                if ($i <= 2) {
                    $proposal->status = 'accepted';
                    $proposal->save();

                    $jobData = Job::factory()->make()->toArray();
                    $job = new Job($jobData);
                    $job->quote_proposal_id = $proposal->id;
                    $job->user_id           = $printer->id;
                    $job->title             = $proposal->title;
                    $job->price             = $proposal->price;
                    $job->quantity          = $proposal->quantity;
                    $job->save();
                }
            }
        }
    }
}