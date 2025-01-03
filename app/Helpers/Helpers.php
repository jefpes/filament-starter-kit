<?php

declare(strict_types = 1);

use Carbon\Carbon;
use Illuminate\Support\Number;

//tools begin
if (!function_exists('auth_user')) {
    function auth_user(): ?\App\Models\User
    {
        $user = \App\Models\User::query()->find(Illuminate\Support\Facades\Auth::id());

        return $user;
    }
}

if (!function_exists('image_path')) {
    /**
     * Generate the storage image path.
     *
     * @param string|null $path
     * @return string
     */
    function image_path(?string $path): string
    {
        return asset('storage/' . $path);
    }
}

if (!function_exists('calculate_compound_interest')) {
    /**
     * Calcula o valor da parcela com juros compostos.
     *
     * @param string $principal Valor principal (sem juros)
     * @param string $rate Taxa de juros em porcentagem
     * @param string $months Número de parcelas
     * @return array<string> Um array com 'total' e 'installment' com 9999.99
     */
    function calculate_compound_interest(string $principal, string $rate, string $months): array
    {
        if ($principal === '0' || $months === '0') {
            return ['total' => '0', 'installment' => '0'];
        }

        // Caso a taxa de juros seja 0%, retorna parcelas sem juros
        if ($rate === '0') {
            $installment = bcdiv($principal, $months, 2);
            $total       = $principal;
        } else {
            $rate            = bcdiv($rate, '100', 10); // Converte a taxa de juros para decimal
            $onePlusRate     = bcadd('1', $rate, 10); // (1 + rate)
            $ratePowerMonths = bcpow($onePlusRate, $months, 10); // (1 + rate)^months
            $numerator       = bcmul(bcmul($principal, $rate, 10), $ratePowerMonths, 10); // principal * rate * (1 + rate)^months
            $denominator     = bcsub($ratePowerMonths, '1', 10); // (1 + rate)^months - 1
            $installment     = bcdiv($numerator, $denominator, 2); // numerator / denominator
            $total           = bcmul($installment, $months, 2); // installment * months
        }

        return [
            'total'       => $total,
            'installment' => $installment,
        ];
    }
}

if (!function_exists('string_money_to_float')) {
    /**
     * 9.999,00 -> 9999.99.
     *
     * @param string $value
     */
    function string_money_to_float(?string $value): string
    {
        if ($value === null || $value === '') {
            return '0';
        }

        return str_replace(['.', ','], ['', '.'], $value);
    }
}

if (!function_exists('spell_monetary')) {
    function spell_monetary(?float $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        // Separa o valor em reais e centavos
        $reais    = floor($value); // Parte inteira
        $centavos = round(($value - $reais) * 100); // Parte decimal

        // Converte a parte inteira (reais) para extenso
        $reaisExtenso = Number::spell($reais, locale: 'br');

        // Definir plural ou singular para reais e centavos
        $reaisText    = $reais == 1 ? 'real' : 'reais';
        $centavosText = $centavos == 1 ? 'centavo' : 'centavos';

        // Se não houver centavos
        if ($centavos == 0) {
            return "$reaisExtenso $reaisText";
        }

        // Se não houver reais
        if ($reais == 0) {
            $centavosExtenso = Number::spell($centavos, locale: 'br');

            return "$centavosExtenso $centavosText";
        }

        // Converte a parte decimal (centavos) para extenso
        $centavosExtenso = Number::spell($centavos, locale: 'br');

        // Retorna o valor completo em extenso
        return "$reaisExtenso $reaisText e $centavosExtenso $centavosText";
    }
}

if (!function_exists('spell_percentage')) {
    function spell_percentage(?float $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Porcentagem não especificada';
        }

        // Verifica se o número é inteiro (não possui parte decimal)
        if (floor($value) == $value) {
            // Converte a parte inteira para texto em português
            $parteInteiraTexto = Number::spell((int)$value, locale: 'br');

            // Retorna o valor com "por cento" para inteiros
            return "$parteInteiraTexto por cento";
        }

        // Caso contrário, separa a parte inteira e a parte decimal
        $parteInteira = floor($value); // Parte inteira do valor
        $parteDecimal = round(($value - $parteInteira) * 100); // Parte decimal, máximo de duas casas

        // Converte a parte inteira para texto em português
        $parteInteiraTexto = Number::spell($parteInteira, locale: 'br');

        // Parte decimal como texto, com dígitos separados (ex: 3 -> "zero três")
        $parteDecimalTexto = str_split(str_pad((string)$parteDecimal, 2, '0', STR_PAD_LEFT)); // Converte para string antes de usar str_pad
        $parteDecimalTexto = implode(' ', array_map(fn ($d) => Number::spell((int) $d, locale: 'br'), $parteDecimalTexto));

        // Combina a parte inteira e decimal com a palavra "porcento"
        return "$parteInteiraTexto vírgula $parteDecimalTexto porcento";
    }
}

if (!function_exists('spell_number')) {
    function spell_number(?float $value): string
    {

        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        // Separa a parte inteira e a parte decimal
        $parteInteira    = floor($value); // Parte inteira
        $parteFracionada = $value - $parteInteira; // Parte fracionária

        // Converte a parte inteira para extenso
        $parteInteiraExtenso = Number::spell($parteInteira, locale: 'br');

        // Caso não haja parte fracionada, retorna apenas a parte inteira
        if ($parteFracionada == 0) {
            return $parteInteiraExtenso;
        }

        // Limita a parte fracionada a dois dígitos, multiplicando por 100
        $parteFracionada = round($parteFracionada * 100);

        // Verifica se a parte fracionada é maior que 0 e trata a exibição
        if ($parteFracionada > 0) {
            $parteFracionadaExtenso = Number::spell($parteFracionada, locale: 'br');

            return "$parteInteiraExtenso vírgula $parteFracionadaExtenso";
        }

        return $parteInteiraExtenso;
    }
}

if (!function_exists('date_format_custom')) {
    function date_format_custom(?string $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        $value = Carbon::parse($value)->format('d/m/Y');

        return $value;
    }
}

if (!function_exists('spell_date')) {
    function spell_date(?string $value, string $locale = 'pt_BR', string $isoFormat = 'LL'): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        $value = Carbon::create($value)->locale($locale)->isoFormat($isoFormat);

        return $value;
    }
}

//tools end

/**tenant begin

if (!function_exists('tenant')) {
    function tenant(): ?Tenant
    {
        return session('tenant');
    }
}

if (!function_exists('subdomain')) {
    function subdomain(): string
    {
        $host = request()->host();

        return str($host)->explode('.')[0] ?? '';
    }
}

if (!function_exists('subdomain_url')) {
    function subdomain_url(string $subdomain, string $path = ''): string
    {
        if (filled($path)) {
            $path = str($path)->start('/')->toString();
        }

        $appUrl = config('app.url');

        $baseUrl = str($appUrl)
            ->replace('http://', '')
            ->replace('https://', '')
            ->toString();

        if (app()->isProduction() === true) {
            $subdomainUrl = "https://{$subdomain}.{$baseUrl}{$path}";
        }

        if (app()->isProduction() === false) {
            $subdomainUrl = "http://{$subdomain}.{$baseUrl}{$path}";
        }

        return $subdomainUrl;
    }
}

if (!function_exists('sanitize')) {
    function sanitize(string $data): string
    {
        return clean_string($data);
    }
}

if (!function_exists('clean_string')) {
    function clean_string(string $string): string
    {
        return preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
}

if (!function_exists('check_cpf')) {
    function check_cpf(string $cpf): bool
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    if (!function_exists('unique_within_tenant_rule')) {
        function unique_within_tenant_rule(mixed $model = null): Closure
        {
            return function ($record, $component) use ($model) {
                return UniqueWithinTenant::make(
                    table: ($model::getModel())::query()->getModel()->getTable(),
                    column: $component->getName(),
                    ignore: $record?->id
                );
            };
        }

    }
}
tenant end
*/
