# Estructura del proyecto

```text
app/
├── Interfaces/
│   ├── Repositories/
│   │   └── UserRepositoryInterface.php
│   │
│   └── Services/
│       └── AuthServiceInterface.php
│
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/
│   │           └── Auth/
│   │               └── AuthController.php
│   │
│   ├── Requests/
│   │   └── Api/
│   │       └── V1/
│   │           └── Auth/
│   │               ├── LoginRequest.php
│   │               └── RegisterRequest.php
│   │
│   └── Resources/
│       └── Api/
│           └── V1/
│               └── Auth/
│                   └── AuthResource.php
│
├── Repositories/
│   └── Eloquent/
│       └── UserRepository.php
│
├── Services/
│   └── AuthService.php
│
├── Models/
│   └── User.php
│
└── Providers/
    └── AppServiceProvider.php

lang/
├── en/
│   └── auth.php
└── es/
    └── auth.php

routes/
└── api.php
```

El controlador valida las peticiones mediante Form Requests y delega la
autenticación en `AuthServiceInterface`. `AuthService` implementa ese contrato
y utiliza `UserRepositoryInterface` para crear usuarios. Las implementaciones
se registran en `AppServiceProvider`.

El login devuelve HTTP 200 y el registro HTTP 201, con `user` y `access_token`.
Las credenciales incorrectas generan una excepción de validación: las peticiones
que esperan JSON reciben HTTP 422. `AuthResource` está disponible, pero el
controlador devuelve actualmente los datos del servicio directamente como JSON.
