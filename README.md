# Document Validation System

A decoupled, rule-based document validation component designed for multi-tenant architectures and dynamic configuration.

## Architecture & Design Patterns

The component is built around a modular, non-intrusive validation pipeline, adhering to SOLID principles and ensuring strict separation of concerns.

*   **Manager / Driver Pattern:** Validation execution is orchestrated by a central `ValidationRuleManager`. The manager dynamically resolves and instantiates specific validation rules (drivers) based on configuration data, injecting required contextual parameters at runtime.
*   **IoC Container:** A lightweight Service Container manages object lifecycles and handles dependency injection across the component, keeping the core validation logic decoupled from concrete implementations.
*   **Extensibility:** Every validation check implements a unified rule interface. New validation logic (e.g., prohibited words, size limits, file types) can be added as isolated drivers without modifying the core execution engine.

## Tech Stack & Quality Tools

*   **PHP 8.4+** – Strict typing, constructor property promotion, and readonly properties.
*   **PHPUnit 10** – Automated unit and integration testing suite.
*   **PHPStan 2.2** – Static analysis configured at the highest level (Level 9) for strict type safety.
*   **Laravel Pint** – Code style linting and automated formatting.
*   **PSR-4** – Standardized class autoloading.