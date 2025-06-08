<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ICategoriaMenuService;
use App\Services\Interfaces\IItemMenuService;
use App\Services\Interfaces\ISucursalService;
use App\Services\Interfaces\IUsuarioService;
use App\Services\Interfaces\IImagenService;
use App\Traits\AuthenticatedUserTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use RuntimeException;

class MenuController extends Controller
{
    use AuthenticatedUserTrait;

    public function __construct(
        private readonly ICategoriaMenuService $categoriaMenuService,
        private readonly IItemMenuService      $itemMenuService,
        private readonly ISucursalService      $sucursalService,
        private readonly IUsuarioService       $usuarioService,
        private readonly IImagenService        $imagenService,
    )
    {
    }

    public function index(): View
    {
        // Obtener el usuario autenticado
        $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());

        // Obtener las sucursales del usuario
        $sucursales = $this->sucursalService->obtenerPorUsuarioId($usuario->id);

        // Obtener categorías activas del tenant
        $categorias = $this->categoriaMenuService->obtenerTodos()
            ->where('tenant_id', $usuario->tenant_id)
            ->whereIn('sucursal_id', $sucursales->pluck('id'));

        $itemsSucursales = $this->itemMenuService->obtenerTodosItemsDisponibles()
            ->filter(function ($item) use ($usuario, $sucursales) {
                return $item->categoriaMenu->tenant_id === $usuario->tenant_id
                    && $sucursales->contains('id', $item->categoriaMenu->sucursal_id);
            });



        // Calcular estadísticas
        $totalItems = $itemsSucursales->count();
        $itemsActivos = $itemsSucursales->where('activo', true)->count();
        $precioPromedio = $itemsSucursales->avg('precio') ?? 0;

        // Obtener el ítem más vendido (por ahora usamos el primero como ejemplo)
        $masVendido = $itemsSucursales->first()?->nombre ?? 'No hay datos';

        $stats = [
            'total_items' => $totalItems,
            'items_activos' => $itemsActivos,
            'precio_promedio' => $precioPromedio,
            'mas_vendido' => $masVendido
        ];

        return view('gerente-sucursal.menu', compact('categorias', 'itemsSucursales', 'stats', 'sucursales'));
    }

    public function showItem(int $id): JsonResponse
    {
        try {
            $item = $this->itemMenuService->obtenerPorId($id);
            if (!$item) {
                return response()->json(['error' => 'Item no encontrado'], 404);
            }

            // Cargar la relación con la categoría
            $item->load('categoriaMenu');

            return response()->json(['item' => $item]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateItem(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria_menu_id' => 'required|exists:categorias_menus,id',
            'disponible' => 'boolean',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['disponible'] = $request->boolean('disponible');
        $data['activo'] = $request->boolean('activo');

        // Procesar la imagen si se ha subido una nueva
        if ($request->hasFile('imagen')) {
            $imagen = $this->imagenService->guardarImagen($request->file('imagen'), 'items_menu');
            if ($imagen) {
                $data['imagen_id'] = $imagen->id;
            }
        }

        $success = $this->itemMenuService->actualizar($id, $data);

        if (!$success) {
            return response()->json(['error' => 'Error al actualizar el item'], 500);
        }

        return response()->json(['message' => 'Item actualizado exitosamente']);
    }

    public function toggleItemActivo(int $id): JsonResponse
    {
        $item = $this->itemMenuService->obtenerPorId($id);
        if (!$item) {
            return response()->json(['error' => 'Item no encontrado'], 404);
        }

        $success = $this->itemMenuService->actualizar($id, ['activo' => !$item->activo]);

        if (!$success) {
            return response()->json(['error' => 'Error al cambiar el estado del item'], 500);
        }

        return response()->json([
            'message' => 'Estado del item actualizado exitosamente',
            'activo' => !$item->activo
        ]);
    }

    public function toggleItemDisponible(int $id): JsonResponse
    {
        $item = $this->itemMenuService->obtenerPorId($id);
        if (!$item) {
            return response()->json(['error' => 'Item no encontrado'], 404);
        }

        $success = $this->itemMenuService->actualizar($id, ['disponible' => !$item->disponible]);

        if (!$success) {
            return response()->json(['error' => 'Error al cambiar la disponibilidad del item'], 500);
        }

        return response()->json([
            'message' => 'Disponibilidad del item actualizada exitosamente',
            'disponible' => !$item->disponible
        ]);
    }

    public function storeItem(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'categoria_menu_id' => 'required|exists:categorias_menus,id',
                'orden_visualizacion' => 'nullable|integer|min:1',
                'disponible' => 'boolean',
                'activo' => 'boolean',
                'imagen' => 'nullable|image|max:2048'
            ]);

            $data = $request->all();
            $data['disponible'] = $request->boolean('disponible', true);
            $data['activo'] = $request->boolean('activo', true);

            // Procesar la imagen si se ha subido una
            if ($request->hasFile('imagen')) {
                $imagen = $this->imagenService->guardarImagen($request->file('imagen'), 'items_menu');
                if ($imagen) {
                    $data['imagen_id'] = $imagen->id;
                }
            }

            $item = $this->itemMenuService->crear($data);

            if (!$item) {
                return response()->json(['error' => 'Error al crear el item'], 500);
            }

            // Cargar la relación con la categoría y la imagen
            $item->load(['categoriaMenu', 'imagen']);

            return response()->json([
                'message' => 'Item creado exitosamente',
                'item' => $item
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeCategoria(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'sucursal_id' => 'required|exists:sucursales,id',
                'orden_visualizacion' => 'nullable|integer|min:1',
                'activo' => 'boolean'
            ]);

            $data = $request->all();
            // Asegurarnos de que activo sea un booleano
            $data['activo'] = filter_var($request->input('activo'), FILTER_VALIDATE_BOOLEAN);
            $usuario = $this->usuarioService->obtenerPorId($this->getCurrentUser()->getAuthIdentifier());
            $data['tenant_id'] = $usuario->tenant_id;

            $categoria = $this->categoriaMenuService->crear($data);

            if (!$categoria) {
                return response()->json(['error' => 'Error al crear la categoría'], 500);
            }

            return response()->json([
                'message' => 'Categoría creada exitosamente',
                'categoria' => $categoria
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCategoria(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $data = $request->all();
        $data['activo'] = $request->boolean('activo');

        $success = $this->categoriaMenuService->actualizar($id, $data);

        if (!$success) {
            return response()->json(['error' => 'Error al actualizar la categoría'], 500);
        }

        return response()->json(['message' => 'Categoría actualizada exitosamente']);
    }

    public function toggleCategoriaActivo(int $id): JsonResponse
    {
        $categoria = $this->categoriaMenuService->obtenerPorId($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }

        $success = $this->categoriaMenuService->actualizar($id, ['activo' => !$categoria->activo]);

        if (!$success) {
            return response()->json(['error' => 'Error al cambiar el estado de la categoría'], 500);
        }

        return response()->json([
            'message' => 'Estado de la categoría actualizado exitosamente',
            'activo' => !$categoria->activo
        ]);
    }

    public function showCategoria(int $id): JsonResponse
    {
        try {
            $categoria = $this->categoriaMenuService->obtenerPorId($id);
            if (!$categoria) {
                return response()->json(['error' => 'Categoría no encontrada'], 404);
            }

            return response()->json(['categoria' => $categoria]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadImagen(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'imagen' => 'required|image|max:2048'
            ]);

            if ($request->hasFile('imagen')) {
                try {
                    $imagen = $request->file('imagen');
                    if ($imagen->isValid()) {
                        // Usar DIRECTORY_SEPARATOR para compatibilidad con Windows
                        $directory = 'imagenes' . DIRECTORY_SEPARATOR . 'items_menu';

                        // Crear directorio si no existe
                        if (!Storage::disk('public')->exists($directory)) {
                            Storage::disk('public')->makeDirectory($directory);
                        }

                        $imagenPath = $imagen->store($directory, 'public');
                        $imagen = $this->imagenService->crear([
                            'url' => str_replace('\\', '/', $imagenPath), // Convertir separadores para URL
                            'activo' => true,
                        ]);

                        if ($imagen) {
                            Log::info('Imagen guardada exitosamente: ' . $imagen->url);
                            Log::info('Path: ' . $directory);
                            return response()->json([
                                'message' => 'Imagen subida exitosamente',
                                'imagen_id' => $imagen->id
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    Log::error('Error al guardar la imagen del item: ' . $e->getMessage());
                    throw new RuntimeException('No se pudo procesar la imagen: ' . $e->getMessage());
                }
            }

            return response()->json(['error' => 'Error al guardar la imagen'], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al subir la imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}
