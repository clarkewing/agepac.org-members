<?php

namespace App\Http\Livewire\Steps;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;
use Torann\GeoIP\Facades\GeoIP;

trait Details
{
    public $birthdate;
    public $birthdate_day = ''; // Ensure select is initially blank
    public $birthdate_month = ''; // Ensure select is initially blank
    public $birthdate_year = ''; // Ensure select is initially blank

    public $gender = ''; // Ensure select is initially blank

    public $phone;

    /**
     * Run the action.
     *
     * @return bool
     */
    public function runDetails(): bool
    {
        $this->setBirthdate();

        $this->validate([
            'gender' => ['required', Rule::in(array_keys(config('council.genders')))],
            'birthdate' => ['required', 'date_format:Y-m-d', 'before:13 years ago'],
            'phone' => ['required', Rule::opinionatedPhone()],
        ]);

        $this->formatPhone();

        return true;
    }

    /**
     * Set birthdate from parts.
     *
     * @return void
     */
    protected function setBirthdate(): void
    {
        if (empty($this->birthdate_day)
            || empty($this->birthdate_month)
            || empty($this->birthdate_year)
        ) {
            return;
        }

        $birthdate = Carbon::createFromDate(
            $this->birthdate_year,
            $this->birthdate_month,
            $this->birthdate_day,
            'UTC'
        );

        $this->birthdate_year = $birthdate->year;
        $this->birthdate_month = $birthdate->month;
        $this->birthdate_day = $birthdate->day;

        $this->birthdate = $birthdate->toDateString();
    }

    /**
     * Set phone to standardized format.
     *
     * @return void
     */
    protected function formatPhone(): void
    {
        $this->phone = PhoneNumber::make($this->phone)
            ->ofCountry('AUTO')
            ->ofCountry('FR')
            ->ofCountry(GeoIP::getLocation(request()->ip())->iso_code)
            ->formatInternational();
    }
}
