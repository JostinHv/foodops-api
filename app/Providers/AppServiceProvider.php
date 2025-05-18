<?php

namespace App\Providers;

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
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     */
    public function register(): void
    {
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
