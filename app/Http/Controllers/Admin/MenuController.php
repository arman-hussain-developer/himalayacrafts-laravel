<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = NavigationMenu::with(['page', 'category', 'parent'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('label', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%")
                        ->orWhere('route_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('location'), fn ($query) => $query->where('location', $request->string('location')))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->boolean('status')))
            ->orderBy('location')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->paginate(15)
            ->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        NavigationMenu::create($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(NavigationMenu $menu)
    {
        return view('admin.menus.edit', $this->formData($menu) + compact('menu'));
    }

    public function update(Request $request, NavigationMenu $menu)
    {
        $validated = $this->validated($request, $menu);

        $menu->update($validated);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(NavigationMenu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu item deleted successfully.');
    }

    private function formData(?NavigationMenu $menu = null): array
    {
        return [
            'types' => [
                'route' => 'Route',
                'page' => 'Page',
                'category' => 'Category',
                'custom_url' => 'Custom URL',
            ],
            'locations' => NavigationMenu::LOCATIONS,
            'allowedRoutes' => NavigationMenu::ALLOWED_ROUTES,
            'pages' => Page::where('status', true)->orderBy('title')->get(),
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'parentMenus' => NavigationMenu::where('location', old('location', $menu?->location ?? 'header'))
                ->when($menu, fn ($query) => $query->where('id', '!=', $menu->id))
                ->orderBy('location')
                ->orderBy('sort_order')
                ->orderBy('label')
                ->get(),
        ];
    }

    private function validated(Request $request, ?NavigationMenu $menu = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(NavigationMenu::TYPES)],
            'url' => ['nullable', 'string', 'max:2048', 'required_if:type,custom_url'],
            'route_name' => ['nullable', 'string', Rule::in(array_keys(NavigationMenu::ALLOWED_ROUTES)), 'required_if:type,route'],
            'page_id' => ['nullable', 'exists:pages,id', 'required_if:type,page'],
            'category_id' => ['nullable', 'exists:categories,id', 'required_if:type,category'],
            'parent_id' => ['nullable', 'exists:navigation_menus,id'],
            'location' => ['required', Rule::in(NavigationMenu::LOCATIONS)],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'open_in_new_tab' => ['required', 'boolean'],
            'is_mega' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        if ($menu && (int) ($validated['parent_id'] ?? 0) === $menu->id) {
            return back()->withErrors(['parent_id' => 'A menu item cannot be its own parent.'])->throwResponse();
        }

        if ($validated['type'] !== 'custom_url') {
            $validated['url'] = null;
        }

        if ($validated['type'] !== 'route') {
            $validated['route_name'] = null;
        }

        if ($validated['type'] !== 'page') {
            $validated['page_id'] = null;
        }

        if ($validated['type'] !== 'category') {
            $validated['category_id'] = null;
        }

        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab');
        $validated['is_mega'] = $request->boolean('is_mega');
        $validated['status'] = $request->boolean('status');

        return $validated;
    }
}
