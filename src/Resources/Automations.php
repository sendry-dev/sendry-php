<?php

declare(strict_types=1);

namespace Sendry\Resources;

final class Automations extends Resource
{
    // -------- Automations --------

    /** @param array<string, mixed> $query */
    public function list(array $query = []): array
    {
        return $this->client->get('/v1/automations', $query) ?? [];
    }

    public function get(string $automationId): array
    {
        return $this->client->get("/v1/automations/{$automationId}") ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function create(array $payload): array
    {
        return $this->client->post('/v1/automations', $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function update(string $automationId, array $payload): array
    {
        return $this->client->patch("/v1/automations/{$automationId}", $payload) ?? [];
    }

    public function delete(string $automationId): array
    {
        return $this->client->delete("/v1/automations/{$automationId}") ?? [];
    }

    public function activate(string $automationId): array
    {
        return $this->client->post("/v1/automations/{$automationId}/activate") ?? [];
    }

    public function pause(string $automationId): array
    {
        return $this->client->post("/v1/automations/{$automationId}/pause") ?? [];
    }

    public function archive(string $automationId): array
    {
        return $this->client->post("/v1/automations/{$automationId}/archive") ?? [];
    }

    // -------- Steps --------

    public function listSteps(string $automationId): array
    {
        return $this->client->get("/v1/automations/{$automationId}/steps") ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function addStep(string $automationId, array $payload): array
    {
        return $this->client->post("/v1/automations/{$automationId}/steps", $payload) ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function updateStep(string $automationId, string $stepId, array $payload): array
    {
        return $this->client->patch(
            "/v1/automations/{$automationId}/steps/{$stepId}",
            $payload,
        ) ?? [];
    }

    public function deleteStep(string $automationId, string $stepId): array
    {
        return $this->client->delete("/v1/automations/{$automationId}/steps/{$stepId}") ?? [];
    }

    // -------- Runs --------

    /** @param array<string, mixed> $query */
    public function listRuns(string $automationId, array $query = []): array
    {
        return $this->client->get("/v1/automations/{$automationId}/runs", $query) ?? [];
    }

    public function getRun(string $automationId, string $runId): array
    {
        return $this->client->get("/v1/automations/{$automationId}/runs/{$runId}") ?? [];
    }

    public function listRunSteps(string $automationId, string $runId): array
    {
        return $this->client->get("/v1/automations/{$automationId}/runs/{$runId}/steps") ?? [];
    }

    public function cancelRun(string $automationId, string $runId): array
    {
        return $this->client->post("/v1/automations/{$automationId}/runs/{$runId}/cancel") ?? [];
    }

    /** @param array<string, mixed> $payload */
    public function createRun(string $automationId, array $payload): array
    {
        return $this->client->post("/v1/automations/{$automationId}/runs", $payload) ?? [];
    }
}
