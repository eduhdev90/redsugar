<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group("Geolocalização", "Endpoints relacionados ao serviço de geolocalização da HERE API")]
#[Authenticated]
class GeoLocationController extends Controller
{
    /**
     * Autocomplete
     */
    #[BodyParam(
        "q",
        "string",
        "Parâmetro para buscar um endereço.",
        required: true,
        example: "Avenida Paulista, 100"
    )]
    #[Response(<<<JSON
        {"items":[{"title":"Brasil, S\u00e3o Paulo, Avenida Paulista","id":"here:af:street:J9oJWU6WZ7bQfksUD-COKD","language":"pt","resultType":"street","address":{"label":"Avenida Paulista, S\u00e3o Paulo - SP, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"S\u00e3o Paulo","street":"Avenida Paulista"},"highlights":{"title":[{"start":8,"end":17},{"start":19,"end":35}],"address":{"label":[{"start":0,"end":16},{"start":18,"end":27}],"city":[{"start":0,"end":9}],"street":[{"start":0,"end":16}]}}},{"title":"Brasil, S\u00e3o Paulo, Americana, Avenida Paulista","id":"here:af:street:BruNpkp4Kjboj71fSPl6YB","language":"pt","resultType":"street","address":{"label":"Avenida Paulista, Americana - SP, 13478-580, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"Americana","street":"Avenida Paulista","postalCode":"13478-580"},"highlights":{"title":[{"start":8,"end":17},{"start":30,"end":46}],"address":{"label":[{"start":0,"end":16}],"state":[{"start":0,"end":9}],"street":[{"start":0,"end":16}]}}},{"title":"Brasil, S\u00e3o Paulo, Pederneiras, Avenida Paulista","id":"here:af:street:MDeQKXLkqQYyGNRutygVuB","language":"pt","resultType":"street","address":{"label":"Avenida Paulista, Pederneiras - SP, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"Pederneiras","district":"Pederneiras","street":"Avenida Paulista"},"highlights":{"title":[{"start":8,"end":17},{"start":32,"end":48}],"address":{"label":[{"start":0,"end":16}],"state":[{"start":0,"end":9}],"street":[{"start":0,"end":16}]}}},{"title":"Brasil, S\u00e3o Paulo, Avenida Paraguassu Paulista","id":"here:af:street:lIZCQe.bzwBlaZ0MBcqxNB","language":"pt","resultType":"street","address":{"label":"Avenida Paraguassu Paulista, Artur Alvim, S\u00e3o Paulo - SP, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"S\u00e3o Paulo","district":"Artur Alvim","street":"Avenida Paraguassu Paulista"},"highlights":{"title":[{"start":8,"end":17},{"start":19,"end":26},{"start":38,"end":46}],"address":{"label":[{"start":0,"end":7},{"start":19,"end":27},{"start":42,"end":51}],"city":[{"start":0,"end":9}],"street":[{"start":0,"end":7},{"start":19,"end":27}]}}},{"title":"Brasil, S\u00e3o Paulo, Paul\u00ednia, Avenida Paulista","id":"here:af:street:-5Sat9A1yGlFfwEEPNReKB","language":"pt","resultType":"street","address":{"label":"Avenida Paulista, Paul\u00ednia - SP, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"Paul\u00ednia","street":"Avenida Paulista"},"highlights":{"title":[{"start":8,"end":17},{"start":29,"end":45}],"address":{"label":[{"start":0,"end":16}],"state":[{"start":0,"end":9}],"street":[{"start":0,"end":16}]}}}]}
    JSON)]
    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required'
        ]);

        $url = config('here.url_autocomplete');
        $params = [];
        $params['q'] = $request->q;
        $params['in'] = 'countryCode:BRA';
        $params['lang'] = 'pt';

        return $this->requestApi($url, ['query' => $params]);
    }

    /**
     * Lookup
     */
    #[BodyParam('id', "string", "Parâmetro para buscar dados completo de um endereço
    selecionado no autocomplete.", required: true, example: "here:af:street:J9oJWU6WZ7bQfksUD-COKD")]
    #[Response(<<<JSON
        {"title":"Avenida Paulista, S\u00e3o Paulo - SP, Brasil","id":"here:af:street:J9oJWU6WZ7bQfksUD-COKD","language":"pt","resultType":"street","address":{"label":"Avenida Paulista, S\u00e3o Paulo - SP, Brasil","countryCode":"BRA","countryName":"Brasil","stateCode":"SP","state":"S\u00e3o Paulo","city":"S\u00e3o Paulo","street":"Avenida Paulista"},"position":{"lat":-23.56317,"lng":-46.65433},"mapView":{"west":-46.66403,"south":-23.57135,"east":-46.64424,"north":-23.55452}}
    JSON)]
    public function lookup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required'
        ]);

        $url = config('here.url_lookup');
        $params = [];
        $params['id'] = $request->id;
        $params['lang'] = 'pt';

        return $this->requestApi($url, ['query' => $params]);
    }

    public function requestApi($url, $params = [], $method = 'GET'): JsonResponse
    {

        // Cria um handler stack que vai aplicar o back-off exponencial
        $handlerStack = HandlerStack::create();
        $handlerStack->push(Middleware::retry(function ($retries, $request, $response, $exception) {
            // Se a resposta for HTTP 429, tenta novamente com um tempo de espera exponencial
            if ($response && $response->getStatusCode() === 429) {
                // Calcula o tempo de espera em segundos, baseado no número de tentativas
                // Você pode personalizar esse fator conforme a sua necessidade
                $waitTime = pow(2, $retries);
                // Aguarda o tempo calculado
                if ($waitTime <= 30) {
                    sleep($waitTime);
                    // Retorna verdadeiro para tentar novamente
                    return true;
                }
            }
            // Se não for HTTP 429, não tenta novamente
            return false;
        }));

        $params['query']['apiKey'] = config('here.key');
        $params['headers']['Accept-Encoding'] = 'gzip';

        // Cria um cliente GuzzleHttp com o handler stack criado
        $client = new Client(['handler' => $handlerStack]);

        // Faz uma requisição GET na API de terceiros
        try {
            $response = $client->request($method, $url, $params);
            // Se a requisição for bem sucedida, faz algo com a resposta
            return response()->json(json_decode($response->getBody()->getContents()));
        } catch (RequestException $e) {
            // Se a requisição falhar, mostra o erro
            return response()->json($e->getMessage());
        }
    }
}
