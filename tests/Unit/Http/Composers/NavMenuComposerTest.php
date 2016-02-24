<?php namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery as m;
// use Caffeinated\Menus\Menu as Caffenated;
use App\Http\Composers\NavMenuComposer;

class NavMenuComposerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->auth = m::mock('Illuminate\Auth\AuthManager');
        $this->gate = m::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->menu = m::mock('Caffeinated\Menus\Menu');

        $this->cfg = [
            'Reports' => ['permissions' => ['isStaff'],'url' => 'reports'],
            'Home' => ['url' => 'home']
        ];
    }

    public function testMakePassingConfig()
    {
        $config = ['Reports' => ['permissions' => ['isStaff'],'url' => 'reports']];

        $menu = m::mock('App\Http\Composers\NavMenuComposer[build,filter]', [$this->app['menu'], $this->config, $this->auth, $this->gate]);
        $menu->shouldReceive('build')->once()->with($config, m::type('Caffeinated\Menus\Builder'));
        $menu->make('Profile', $config);
    }

    public function testMakeUsingConfig()
    {
        $collect = m::mock('Caffeinated\Menus\Collection');
        $caff = m::mock('Caffeinated\Menus\Menu');
        $item = m::mock('Caffeinated\Menus\Item');
        $builder = m::mock('Caffeinated\Menus\Builder');

        $caff->shouldReceive('make')->once()->with(
            'Profile',
            m::on(function($callable) use ($builder) {
                $callable($builder);
                return true;
            })
        )->andReturn($collect);
        $collect->shouldReceive('filter')
            ->with(m::on(function($callable) use ($item) {
                $callable($item);
                return true;
            })
            )->once();

        $config = ['Reports' => ['permissions' => ['isStaff'],'url' => 'reports']];
        $this->config->shouldReceive('get')->with('menu')->once()->andReturn($config);
        $menu = m::mock('App\Http\Composers\NavMenuComposer[build,filter]', [$caff, $this->config, $this->auth, $this->gate]);
        $menu->shouldReceive('build')->once()->with($config, $builder);
        $menu->shouldReceive('filter')->once()->with($item);
        $menu->make('Profile');

    }

    public function testBuildWithUrl()
    {
        $builder = m::mock('Caffeinated\Menus\Builder');
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $builder->shouldReceive('add')->once()->with('Reports', 'reports')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Home', 'home')->andReturn($builder);
        $builder->shouldReceive('data')->once()->with('permissions', ['isStaff']);
        $builder->shouldReceive('data')->once()->with('permissions', null);
        $menu->build($this->cfg, $builder);

    }

    public function testBuildWithRoute()
    {
        unset($this->cfg['Reports']['url']);
        $this->cfg['Reports']['route'] = 'reports';
        $builder = m::mock('Caffeinated\Menus\Builder');
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $builder->shouldReceive('add')->once()->with('Reports', ['route' => 'reports'])->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Home', 'home')->andReturn($builder);
        $builder->shouldReceive('data')->once()->with('permissions', ['isStaff']);
        $builder->shouldReceive('data')->once()->with('permissions', null);
        $menu->build($this->cfg, $builder);
    }

    public function testBuildWithNamespace()
    {
        $builder = m::mock('Caffeinated\Menus\Builder');
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $builder->shouldReceive('get')->twice()->with('profile')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Reports', 'reports')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Home', 'home')->andReturn($builder);
        $builder->shouldReceive('data')->once()->with('permissions', ['isStaff']);
        $builder->shouldReceive('data')->once()->with('permissions', null);
        $menu->build($this->cfg, $builder, 'Profile');
    }

    public function testBuildWithChildren()
    {
        $this->cfg['Reports']['children'] = ['Summary' => ['url' => 'summary']];
        $builder = m::mock('Caffeinated\Menus\Builder');
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $builder->shouldReceive('get')->once()->with('reports')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Reports', 'reports')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Home', 'home')->andReturn($builder);
        $builder->shouldReceive('add')->once()->with('Summary', 'summary')->andReturn($builder);
        $builder->shouldReceive('data')->once()->with('permissions', ['isStaff']);
        $builder->shouldReceive('data')->twice()->with('permissions', null);
        $menu->build($this->cfg, $builder);
        //Caffeinated\Menus\Item
    }

    public function testFilterNoAuth()
    {
        $item = m::mock('Caffeinated\Menus\Item');
        $this->auth->shouldReceive('check')->withNoArgs()->andReturn(false);
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $result = $menu->filter($item);
        $this->assertFalse($result);
    }

    public function testFilterNoPermissionsAttribute()
    {
        $item = m::mock();
        $item->data = null;
        $this->auth->shouldReceive('check')->withNoArgs()->andReturn(true);
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $result = $menu->filter($item);
        $this->assertTrue($result);
    }

    public function testFilterHasNoPermissions()
    {
        $item = m::mock();
        $item->data = ['permissions' => ['isStaff', 'isAdmin', 'manageSystem']];
        $item->shouldReceive('data')->once()->with('permissions')->andReturn($item->data['permissions']);
        $this->auth->shouldReceive('check')->withNoArgs()->andReturn(true);
        $this->gate->shouldReceive('allows')->once()->with('isStaff')->andReturn(false);
        $this->gate->shouldReceive('allows')->once()->with('isAdmin')->andReturn(false);
        $this->gate->shouldReceive('allows')->once()->with('manageSystem')->andReturn(false);
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $result = $menu->filter($item);
        $this->assertFalse($result);
    }

    public function testFilterHasPermissions()
    {
        $item = m::mock();
        $item->data = ['permissions' => ['isStaff', 'isAdmin', 'manageSystem']];
        $item->shouldReceive('data')->once()->with('permissions')->andReturn($item->data['permissions']);
        $this->auth->shouldReceive('check')->withNoArgs()->andReturn(true);
        $this->gate->shouldReceive('allows')->once()->with('isStaff')->andReturn(false);
        $this->gate->shouldReceive('allows')->once()->with('isAdmin')->andReturn(false);
        $this->gate->shouldReceive('allows')->once()->with('manageSystem')->andReturn(true);
        $menu = new NavMenuComposer($this->menu, $this->config, $this->auth, $this->gate);
        $result = $menu->filter($item);
        $this->assertTrue($result);
    }
}
