<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use libphonenumber\NumberParseException;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserFieldsImport extends LegacyDBImport implements OnEachRow
{
    /**
     * @param  \Maatwebsite\Excel\Row  $row
     * @return void
     */
    public function onRow(Row $row)
    {
        $user = User::find($row['userid']);

        $user->first_name = Str::nameCase($row['field5']);

        $user->last_name = Str::nameCase($row['field6']);

        $user->class_course = $row['field7'] === 'Non EPL'
            ? null
            : [
                'EPL-S' => 'EPL/S',
                'EPL-U' => 'EPL/U',
                'EPL-P' => 'EPL/P',
                'C. ATPL' => 'Cursus Prépa ATPL',
            ][$row['field7']];

        $user->class_year = $row['field8'];

        try {
            $user->phone = PhoneNumber::make($row['field19'])
                ->ofCountry('AUTO')
                ->ofCountry('FR')
                ->formatInternational();
        } catch (NumberParseException $e) {
            // Leave number null.
        }

        $user->save();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'userid' => ['required', 'integer', Rule::exists('users', 'id')],
            'field5' => ['required', 'string', 'max:255'],
            'field6' => ['required', 'string', 'max:255'],
            'field7' => ['required', Rule::in(['Non EPL', 'EPL-S', 'EPL-U', 'EPL-P', 'C. ATPL'])],
            'field8' => ['required', 'digits:4'],
            'field19' => [
                'nullable',
                Rule::phone()
                    ->detect() // Auto-detect country if country code supplied
                    ->country('FR'), // Fallback to France if unable to auto-detect
            ],
        ];
    }
}
