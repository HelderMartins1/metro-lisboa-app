<?php

namespace App\Http\Controllers;

use App\Enums\BlueLine;
use App\Enums\GreenLine;
use App\Enums\RedLine;
use App\Enums\YellowLine;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class InfoController extends Controller
{
    private string $url;

    private string $token;

    private array $headers;

    private array $options;

    public function __construct()
    {
        $this->url = config('global.url');
        $this->token = config('global.token');
        $this->headers = ['Authorization' => 'Bearer '.$this->token];
        $this->options = ['verify' => false];
    }

    public function allLines()
    {
        $request = Http::withOptions($this->options)
            ->withHeaders($this->headers)
            ->get($this->url.'estadoLinha/todos');
        if ($request->json('codigo') == ! 200) {
            return response()->json([
                'error' => 'Unable to get lines status',
                'status' => $request->json('codigo'),
            ]);
        }
        $resp = $request->json('resposta');

        $info = [
            'amarela' => $resp['amarela_curta'],
            'azul' => $resp['azul_curta'],
            'vermelha' => $resp['vermelha_curta'],
            'verde' => $resp['verde_curta'],
        ];

        return response()->json($info);
    }

    public function allDestinations()
    {
        $request = Http::withOptions($this->options)
            ->withHeaders($this->headers)
            ->get($this->url.'infoDestinos/todos');
        if ($request->json('codigo') == ! 200) {
            return response()->json([
                'error' => 'Unable to get all destinations',
                'status' => $request->json('codigo'),
            ]);
        }
        $destinations = Cache::remember('destinations', config('cache.ttl.l'), function () use ($request) {
            return $request->json('resposta');
        });
        $mappedDestinations = ['yellow' => [], 'blue' => [], 'red' => [], 'green' => []];

        $blueLineStations = array_map(fn ($case) => $case->value, BlueLine::cases());
        $greenLineStations = array_map(fn ($case) => $case->value, GreenLine::cases());
        $redLineStations = array_map(fn ($case) => $case->value, RedLine::cases());
        $yellowLineStations = array_map(fn ($case) => $case->value, YellowLine::cases());

        foreach ($destinations as $destination) {
            if (in_array($destination['nome_destino'], $blueLineStations)) {
                $mappedDestinations['blue'][] = $destination;
            }
            if (in_array($destination['nome_destino'], $greenLineStations)) {
                $mappedDestinations['green'][] = $destination;
            }
            if (in_array($destination['nome_destino'], $redLineStations)) {
                $mappedDestinations['red'][] = $destination;
            }
            if (in_array($destination['nome_destino'], $yellowLineStations)) {
                $mappedDestinations['yellow'][] = $destination;
            }
        }
        return response()->json($mappedDestinations);
    }

    public function stations(string $line)
    {
        $stations = match ($line) {
            'yellow' => collect(YellowLine::cases())->mapWithKeys(fn ($case) => [$case->name => $case->value])->toArray(),
            'green' => collect(GreenLine::cases())->mapWithKeys(fn ($case) => [$case->name => $case->value])->toArray(),
            'blue' => collect(BlueLine::cases())->mapWithKeys(fn ($case) => [$case->name => $case->value])->toArray(),
            'red' => collect(RedLine::cases())->mapWithKeys(fn ($case) => [$case->name => $case->value])->toArray(),
            default => []
        };

        return response()->json($stations);
    }

    public function trains(string $station)
    {
        $request = Http::withOptions($this->options)
            ->withHeaders($this->headers)
            ->get($this->url."tempoEspera/Estacao/$station");
        if ($request->json('codigo') == ! 200) {
            return response()->json([
                'error' => 'Unable to get station times',
                'status' => $request->json('codigo'),
            ]);
        }
        $resp = $request->json('resposta');

        return response()->json($resp);
    }
}
