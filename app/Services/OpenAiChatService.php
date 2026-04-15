<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiChatService
{
    public function isConfigured(): bool
    {
        return filled(config('services.openai.api_key'));
    }

    /**
     * @param  array<string, mixed>  $context
     * @param  array<int, array{role: string, message: string}>  $history
     */
    public function generateReply(
        string $message,
        array $context,
        string $intent,
        string $groundedAnswer,
        array $history = []
    ): ?string {
        if (! $this->isConfigured()) {
            return null;
        }

        $input = [
            $this->makeInputMessage('system', $this->systemPrompt($context)),
        ];

        foreach (array_slice($history, -8) as $item) {
            if (! in_array($item['role'] ?? '', ['user', 'assistant'], true) || blank($item['message'] ?? '')) {
                continue;
            }

            $input[] = $this->makeInputMessage($item['role'], $item['message']);
        }

        $input[] = $this->makeInputMessage('user', $this->userPrompt($message, $intent, $groundedAnswer, $context));

        try {
            $response = Http::timeout((int) config('services.openai.timeout', 20))
                ->withToken((string) config('services.openai.api_key'))
                ->acceptJson()
                ->post(rtrim((string) config('services.openai.base_url'), '/').'/responses', [
                    'model' => (string) config('services.openai.model', 'gpt-4.1-mini'),
                    'input' => $input,
                    'temperature' => 0.2,
                ]);

            if (! $response->successful()) {
                Log::warning('OpenAI chatbot request failed.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            return $this->extractText($response->json());
        } catch (\Throwable $exception) {
            Log::warning('OpenAI chatbot request exception.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function systemPrompt(array $context): string
    {
        $roleName = $context['role']['name'] ?? 'Pengguna';
        $scopeSummary = $context['scope']['scope_summary'] ?? 'Akses dibatasi sesuai role.';
        $permissions = $context['permissions'] ?? [];

        return implode("\n", [
            'Kamu adalah AI assistant untuk aplikasi InfraSPH.',
            'Jawab dalam Bahasa Indonesia yang natural, hangat, singkat, dan membantu.',
            'Bersikap seperti asisten website yang profesional dan cepat tanggap, bukan seperti bot kaku.',
            'Kamu wajib mematuhi RBAC dan privasi data.',
            'Jangan pernah mengarang data, jangan memberi akses di luar fakta yang diberikan, dan jangan menampilkan query/database mentah.',
            'Jika fakta terbatas, katakan dengan jujur bahwa informasi yang tersedia terbatas pada akses user.',
            'Gunakan hanya fakta yang diberikan dalam prompt user terakhir sebagai sumber jawaban.',
            'Untuk sapaan ringan, ucapan terima kasih, atau penutupan percakapan, balas secara natural tanpa berlebihan.',
            'Jika user meminta bantuan, arahkan ke tindakan yang bisa dilakukan di dashboard secara jelas.',
            'Hindari paragraf panjang. Utamakan 1-3 kalimat yang jelas.',
            'Konteks role saat ini: '.$roleName.'.',
            'Ruang lingkup akses: '.$scopeSummary,
            'Izin penting: '.json_encode($permissions, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function userPrompt(string $message, string $intent, string $groundedAnswer, array $context): string
    {
        return implode("\n\n", [
            'Pertanyaan user: '.$message,
            'Intent terdeteksi: '.$intent,
            'Fakta aman dari backend: '.$groundedAnswer,
            'Ringkasan konteks aman: '.json_encode([
                'user' => $context['user'] ?? [],
                'role' => $context['role'] ?? [],
                'scope' => [
                    'assigned_room_names' => $context['scope']['assigned_room_names'] ?? [],
                    'scope_summary' => $context['scope']['scope_summary'] ?? null,
                ],
            ], JSON_UNESCAPED_UNICODE),
            'Tugasmu: jawab pertanyaan user dengan ramah berdasarkan fakta aman di atas saja. Jika user meminta sesuatu di luar fakta itu, arahkan ke batas aksesnya. Jika pertanyaan sederhana seperti sapaan atau ucapan terima kasih, balas secara natural namun tetap singkat.',
        ]);
    }

    private function makeInputMessage(string $role, string $text): array
    {
        return [
            'role' => $role,
            'content' => [
                [
                    'type' => 'input_text',
                    'text' => $text,
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function extractText(array $payload): ?string
    {
        if (filled($payload['output_text'] ?? null)) {
            return trim((string) $payload['output_text']);
        }

        foreach (($payload['output'] ?? []) as $outputItem) {
            foreach (($outputItem['content'] ?? []) as $contentItem) {
                $text = $contentItem['text'] ?? null;

                if (filled($text)) {
                    return trim((string) $text);
                }
            }
        }

        return null;
    }
}
