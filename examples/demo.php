<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application;
use App\Validators\DocumentValidatorFactory;
use App\DTO\{
    Document,
    Tenant,
    TenantRule,
    ValidationError,
    ValidationResult};
use App\Repositories\{
    File\FileDocumentRepository,
    File\FileTenantRepository,
    File\FileTenantRuleRepository
};
use App\Services\{
    DocumentService,
    DocumentValidationService,
    TenantService,
    TenantRuleService
};
use App\Support\ColorfulLogger;

class Demo
{

    const array DEMO_NAMES = [
        'doc_001' => 'Valid document',
        'doc_002' => 'A document that contains prohibited terms',
        'doc_003' => 'A document that is too large and does not have the necessary metadata',
    ];

    private ColorfulLogger $logger;

    private TenantService     $tenantService;
    private TenantRuleService $tenantRuleService;

    private DocumentService           $documentService;
    private DocumentValidationService $validationService;

    public function __construct()
    {
        $this->logger = new ColorfulLogger();
        $this->initializeServices();
    }

    private function initializeServices(): void
    {
        $tenantRepository     = new FileTenantRepository(Application::pathTo('data/tenants.json'));
        $documentRepository   = new FileDocumentRepository(Application::pathTo('data/documents.json'));
        $tenantRuleRepository = new FileTenantRuleRepository(Application::pathTo('data/tenant-rules.json'));

        $documentValidatorFactory = new DocumentValidatorFactory();

        $this->tenantService     = new TenantService($tenantRepository);
        $this->tenantRuleService = new TenantRuleService($tenantRuleRepository);

        $this->documentService   = new DocumentService($documentRepository);
        $this->validationService = new DocumentValidationService($documentValidatorFactory);
    }

    public function main(): void
    {
        $this->logger->log("{title}--- Document Validation Demo (File Repository) ---{reset}\n\n");

        [$tenants, $documentsByTenant, $rulesByTenant] = $this->getTenantsData();
        foreach ($tenants as $tenant) {
            $rules     = $rulesByTenant[$tenant->id];
            $documents = $documentsByTenant[$tenant->id];
            $this->validateDocuments($documents, $rules);
        }
    }

    private function getTenantsData(): array
    {
        $tenants   = $this->tenantService->getAllTenants();
        $tenantIds = array_column($tenants, 'id');

        $documents = $this->documentService->allTenantDocuments($tenantIds);
        $rules     = $this->tenantRuleService->getTenantRules($tenantIds);

        $documentsByTenant = [];
        $rulesByTenant     = [];

        foreach ($tenantIds as $tenantId) {
            $documentsByTenant[$tenantId] = array_filter($documents, fn($doc) => $doc->tenantId === $tenantId);
            $rulesByTenant[$tenantId]     = array_filter($rules, fn($rule) => $rule->tenantId === $tenantId);
        }

        return [$tenants, $documentsByTenant, $rulesByTenant];
    }

    /**
     * @param array<Document> $documents
     * @param array<TenantRule> $rules
     */
    private function validateDocuments(array $documents, array $rules): void
    {
        foreach ($documents as $document) {
            $result = $this->validationService->validate($document, $rules);
            $this->report($document, $result);
        }
    }

    private function report(Document $document, ValidationResult $result): void
    {
        $this->logger->log("Test: {info}%s{reset}\n", self::DEMO_NAMES[$document->id]);
        $statusColor = $result->isValid ? '{success}' : '{fail}';
        $statusText  = $result->isValid ? 'YES' : 'NO';

        $this->logger->log("Is Valid: %s%s{reset}\n", $statusColor, $statusText);
        if (!$result->isValid) $this->reportErrors($result->errors);

        echo "\n";
    }

    /**
     * @param array<string, ValidationError> $errors
     */
    private function reportErrors(array $errors): void
    {
        $this->logger->log("{fail}Errors collected:{reset}\n");
        foreach ($errors as $error) {
            $this->logger->log(" {fail}- [%s]:{reset} %s\n", $error->ruleName, $error->message);
        }
    }
}

$demo = new Demo();
$demo->main();
