<?php
namespace Akasima\RichPanel;

use Illuminate\Queue\Connectors\DatabaseConnector;
use Schema;
use Illuminate\Database\Schema\Blueprint;
use Artisan;
use XeFrontend;
use XePresenter;
use Route;
use Xpressengine\Http\Request;
use Xpressengine\Plugin\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        Route::settings($this->getId(), function () {
            Route::get('/', ['as' => 'manage.rich_panel.index', 'uses' => 'ManagerController@index']);
            Route::post('store', ['as' => 'manage.rich_panel.store', 'uses' => 'ManagerController@store']);

        }, ['namespace' => __NAMESPACE__]);

        $this->configQueueDriver();
        app('queue')->addConnector('rich_panel', function () {
            return new DatabaseConnector(app('db'));
        });
    }

    protected function configQueueDriver()
    {
        app('config')->set('queue.connections.rich_panel', [
            'driver' => 'database',
            'table' => 'rich_panel',
            'queue' => 'default',
            'expire' => 60,
        ]);
    }

    public function getSettingsURI()
    {
        return route('manage.rich_panel.index');
    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
        // implement code

        parent::activate($installedVersion);
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
        $this->createRichPanelTable();

        // implement code

        parent::install();
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @param string $installedVersion 이 플러그인의 현재 설치된 버전정보
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled($installedVersion = null)
    {
        // implement code

        return parent::checkInstalled($installedVersion);
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function update($installedVersion = null)
    {
        // implement code

        parent::update($installedVersion);
    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @param string $currentVersion 현재 설치된 버전
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated($currentVersion = null)
    {
        return true;
    }

    /**
     * Database job table 생성
     *
     * @return void
     */
    public function createRichPanelTable()
    {
        if (Schema::hasTable('rich_panel') === false) {
            Schema::create('rich_panel', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue');
                $table->longText('payload');
                $table->tinyInteger('attempts')->unsigned();
                $table->tinyInteger('reserved')->unsigned();
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
                $table->index(['queue', 'reserved', 'reserved_at']);
            });
        }
    }

    /**
     * Database job failed table 생성
     *
     * @return void
     */
    public function createFailedJobsTable()
    {
        if (Schema::hasTable('failed_jobs') === false) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->increments('id');
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->timestamp('failed_at')->useCurrent();
            });
        }
    }
}
