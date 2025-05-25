<?php

namespace App\Providers;

use App\Http\Middleware\CheckAuth;
use App\Repositories\Implementations\AsignacionPersonalRepository;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Implementations\CategoriaMenuRepository;
use App\Repositories\Implementations\EstadoMesaRepository;
use App\Repositories\Implementations\EstadoOrdenRepository;
use App\Repositories\Implementations\FacturaRepository;
use App\Repositories\Implementations\GrupoRestaurantesRepository;
use App\Repositories\Implementations\HistorialPagoSuscripcionRepository;
use App\Repositories\Implementations\IgvRepository;
use App\Repositories\Implementations\ImagenRepository;
use App\Repositories\Implementations\ItemMenuRepository;
use App\Repositories\Implementations\ItemOrdenRepository;
use App\Repositories\Implementations\LimiteUsoRepository;
use App\Repositories\Implementations\MesaRepository;
use App\Repositories\Implementations\MetodoPagoRepository;
use App\Repositories\Implementations\OrdenRepository;
use App\Repositories\Implementations\PlanSuscripcionRepository;
use App\Repositories\Implementations\ReservaRepository;
use App\Repositories\Implementations\RestauranteRepository;
use App\Repositories\Implementations\RolRepository;
use App\Repositories\Implementations\SucursalRepository;
use App\Repositories\Implementations\TenantRepository;
use App\Repositories\Implementations\TenantSuscripcionRepository;
use App\Repositories\Implementations\UsuarioRepository;
use App\Repositories\Implementations\UsuarioRolRepository;
use App\Repositories\Interfaces\IAsignacionPersonalRepository;
use App\Repositories\Interfaces\IBaseRepository;
use App\Repositories\Interfaces\ICategoriaMenuRepository;
use App\Repositories\Interfaces\IEstadoMesaRepository;
use App\Repositories\Interfaces\IEstadoOrdenRepository;
use App\Repositories\Interfaces\IFacturaRepository;
use App\Repositories\Interfaces\IGrupoRestaurantesRepository;
use App\Repositories\Interfaces\IHistorialPagoSuscripcionRepository;
use App\Repositories\Interfaces\IIgvRepository;
use App\Repositories\Interfaces\IImagenRepository;
use App\Repositories\Interfaces\IItemMenuRepository;
use App\Repositories\Interfaces\IItemOrdenRepository;
use App\Repositories\Interfaces\ILimiteUsoRepository;
use App\Repositories\Interfaces\IMesaRepository;
use App\Repositories\Interfaces\IMetodoPagoRepository;
use App\Repositories\Interfaces\IOrdenRepository;
use App\Repositories\Interfaces\IPlanSuscripcionRepository;
use App\Repositories\Interfaces\IReservaRepository;
use App\Repositories\Interfaces\IRestauranteRepository;
use App\Repositories\Interfaces\IRolRepository;
use App\Repositories\Interfaces\ISucursalRepository;
use App\Repositories\Interfaces\ITenantRepository;
use App\Repositories\Interfaces\ITenantSuscripcionRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Repositories\Interfaces\IUsuarioRolRepository;
use App\Services\Implementations\AsignacionPersonalService;
use App\Services\Implementations\Auth\AuthService;
use App\Services\Implementations\Auth\JwtManager;
use App\Services\Implementations\CategoriaMenuService;
use App\Services\Implementations\EstadoMesaService;
use App\Services\Implementations\EstadoOrdenService;
use App\Services\Implementations\FacturaService;
use App\Services\Implementations\GrupoRestaurantesService;
use App\Services\Implementations\HistorialPagoSuscripcionService;
use App\Services\Implementations\IgvService;
use App\Services\Implementations\ImagenService;
use App\Services\Implementations\ItemMenuService;
use App\Services\Implementations\ItemOrdenService;
use App\Services\Implementations\LimiteUsoService;
use App\Services\Implementations\MesaService;
use App\Services\Implementations\MetodoPagoService;
use App\Services\Implementations\OrdenService;
use App\Services\Implementations\PlanSuscripcionService;
use App\Services\Implementations\ReservaService;
use App\Services\Implementations\RestauranteService;
use App\Services\Implementations\RolService;
use App\Services\Implementations\SucursalService;
use App\Services\Implementations\TenantService;
use App\Services\Implementations\TenantSuscripcionService;
use App\Services\Implementations\UsuarioRolService;
use App\Services\Implementations\UsuarioService;
use App\Services\Interfaces\IAsignacionPersonalService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ICategoriaMenuService;
use App\Services\Interfaces\IEstadoMesaService;
use App\Services\Interfaces\IEstadoOrdenService;
use App\Services\Interfaces\IFacturaService;
use App\Services\Interfaces\IGrupoRestaurantesService;
use App\Services\Interfaces\IHistorialPagoSuscripcionService;
use App\Services\Interfaces\IIgvService;
use App\Services\Interfaces\IImagenService;
use App\Services\Interfaces\IItemMenuService;
use App\Services\Interfaces\IItemOrdenService;
use App\Services\Interfaces\IJwtManager;
use App\Services\Interfaces\ILimiteUsoService;
use App\Services\Interfaces\IMesaService;
use App\Services\Interfaces\IMetodoPagoService;
use App\Services\Interfaces\IOrdenService;
use App\Services\Interfaces\IPlanSuscripcionService;
use App\Services\Interfaces\IReservaService;
use App\Services\Interfaces\IRestauranteService;
use App\Services\Interfaces\IRolService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\ITenantService;
use App\Services\Interfaces\ITenantSuscripcionService;
use App\Services\Interfaces\IUsuarioRolService;
use App\Services\Interfaces\IUsuarioService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application Services.
     */
    public function register(): void
    {
        // Registra los servicios en el contenedor de servicios
        $this->app->bind(
            IAuthService::class,
            AuthService::class,
        );
        $this->app->bind(
            IJwtManager::class,
            JwtManager::class,
        );
        $this->app->bind(
            IAsignacionPersonalService::class,
            AsignacionPersonalService::class,
        );
        $this->app->bind(
            ICategoriaMenuService::class,
            CategoriaMenuService::class,
        );
        $this->app->bind(
            IEstadoMesaService::class,
            EstadoMesaService::class,
        );
        $this->app->bind(
            IEstadoOrdenService::class,
            EstadoOrdenService::class,
        );
        $this->app->bind(
            IFacturaService::class,
            FacturaService::class,
        );
        $this->app->bind(
            IGrupoRestaurantesService::class,
            GrupoRestaurantesService::class,
        );
        $this->app->bind(
            IHistorialPagoSuscripcionService::class,
            HistorialPagoSuscripcionService::class,
        );
        $this->app->bind(
            IIgvService::class,
            IgvService::class,
        );
        $this->app->bind(
            IImagenService::class,
            ImagenService::class,
        );
        $this->app->bind(
            IItemMenuService::class,
            ItemMenuService::class,
        );
        $this->app->bind(
            IItemOrdenService::class,
            ItemOrdenService::class,
        );
        $this->app->bind(
            ILimiteUsoService::class,
            LimiteUsoService::class,
        );
        $this->app->bind(
            IMesaService::class,
            MesaService::class,
        );
        $this->app->bind(
            IMetodoPagoService::class,
            MetodoPagoService::class,
        );
        $this->app->bind(
            IOrdenService::class,
            OrdenService::class,
        );
        $this->app->bind(
            IPlanSuscripcionService::class,
            PlanSuscripcionService::class,
        );
        $this->app->bind(
            IReservaService::class,
            ReservaService::class,
        );
        $this->app->bind(
            IRestauranteService::class,
            RestauranteService::class,
        );
        $this->app->bind(
            IRolService::class,
            RolService::class,
        );
        $this->app->bind(
            ISucursalService::class,
            SucursalService::class,
        );
        $this->app->bind(
            ITenantService::class,
            TenantService::class,
        );
        $this->app->bind(
            ITenantSuscripcionService::class,
            TenantSuscripcionService::class,
        );
        $this->app->bind(
            IUsuarioRolService::class,
            UsuarioRolService::class,
        );
        $this->app->bind(
            IUsuarioService::class,
            UsuarioService::class,
        );

        // Registra los repositorios en el contenedor de servicios
        $this->app->bind(
            IBaseRepository::class,
            BaseRepository::class,
        );
        $this->app->bind(
            IAsignacionPersonalRepository::class,
            AsignacionPersonalRepository::class,
        );
        $this->app->bind(
            ICategoriaMenuRepository::class,
            CategoriaMenuRepository::class,
        );
        $this->app->bind(
            IEstadoMesaRepository::class,
            EstadoMesaRepository::class,
        );
        $this->app->bind(
            IEstadoOrdenRepository::class,
            EstadoOrdenRepository::class,
        );
        $this->app->bind(
            IFacturaRepository::class,
            FacturaRepository::class,
        );
        $this->app->bind(
            IGrupoRestaurantesRepository::class,
            GrupoRestaurantesRepository::class,
        );
        $this->app->bind(
            IHistorialPagoSuscripcionRepository::class,
            HistorialPagoSuscripcionRepository::class,
        );
        $this->app->bind(
            IIgvRepository::class,
            IgvRepository::class,
        );
        $this->app->bind(
            IImagenRepository::class,
            ImagenRepository::class,
        );
        $this->app->bind(
            IItemMenuRepository::class,
            ItemMenuRepository::class,
        );
        $this->app->bind(
            IItemOrdenRepository::class,
            ItemOrdenRepository::class,
        );
        $this->app->bind(
            ILimiteUsoRepository::class,
            LimiteUsoRepository::class,
        );
        $this->app->bind(
            IMesaRepository::class,
            MesaRepository::class,
        );
        $this->app->bind(
            IMetodoPagoRepository::class,
            MetodoPagoRepository::class,
        );
        $this->app->bind(
            IOrdenRepository::class,
            OrdenRepository::class,
        );
        $this->app->bind(
            IPlanSuscripcionRepository::class,
            PlanSuscripcionRepository::class,
        );
        $this->app->bind(
            IReservaRepository::class,
            ReservaRepository::class,
        );
        $this->app->bind(
            IRestauranteRepository::class,
            RestauranteRepository::class,
        );
        $this->app->bind(
            IRolRepository::class,
            RolRepository::class,
        );
        $this->app->bind(
            ISucursalRepository::class,
            SucursalRepository::class
        );
        $this->app->bind(
            ITenantRepository::class,
            TenantRepository::class,
        );
        $this->app->bind(
            ITenantSuscripcionRepository::class,
            TenantSuscripcionRepository::class,
        );
        $this->app->bind(
            IUsuarioRepository::class,
            UsuarioRepository::class,
        );
        $this->app->bind(
            IUsuarioRolRepository::class,
            UsuarioRolRepository::class,
        );
    }

    /**
     * Bootstrap any application Services.
     */
    public function boot(): void
    {
        //
    }
}
