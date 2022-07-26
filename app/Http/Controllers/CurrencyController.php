<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use App\Services\CbrService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller {

    private CbrService $cbrService;

    public function __construct(CbrService $cbrService) {
        $this->cbrService = $cbrService;
    }

    public function index(Request $request) {

        $dateStart = Carbon::parse($request->get('from'))->startOfDay();
        $dateEnd = Carbon::parse($request->get('to'))->startOfDay();

        $list = $this->cbrService->getList($dateStart, $dateEnd);

        foreach ($list as $el) {
            if (!$this->updateOrCreateCurrency($el)) {
                // answer exception null - ?????
                return response(null, 500);
            }
        }
        return response(Currency::all(), 203);
    }

    public function getByDateAndValuteId(Request $request) {

        $valuteID = $request->get('valuteID');
        $dateStart = Carbon::parse($request->get('from'))->startOfDay();
        $dateEnd = Carbon::parse($request->get('to'))->startOfDay();

        $currencies = Currency::where('valuteID', $valuteID)
                                ->whereBetween('date', [$dateStart, $dateEnd])
                                ->orderBy('date')
                                ->get();

        return $currencies;
    }

    public function createCurrency(CurrencyRequest $request) {

        $currency = new Currency();
        $currency->valuteID = $request['valuteID'];
        $currency->numCode = $request['numCode'];
        $currency->nominal = $request['nominal'];
        $currency->сharCode = $request['сharCode'];
        $currency->name = $request['name'];
        $currency->value = $request['value'];
        $currency->date = $request['date'];

        if ($currency->save()) {
            return response($currency, 203);
        } else {
            return response(500, 500);
        }
    }

    public function updateCurrency(CurrencyRequest $request) {
        
    }

    private function updateOrCreateCurrency(Currency $currency): bool {

        try {
            Currency::updateOrCreate(
                [
                    'valuteID' => $currency['valuteID'],
                    'date' => $currency['date'],
                    'nominal' => $currency['nominal'],
                    'сharCode' => $currency['сharCode'],
                    'name' => $currency['name'],
                    'numCode' => $currency['numCode'],
                ],
                [
                    'value' => $currency['value']
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
