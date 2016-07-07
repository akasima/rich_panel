<?php
namespace Akasima\RichPanel;

use App\Http\Controllers\Controller;
use XePresenter;
use Queue;
use Artisan;
use Mail;
use Xpressengine\Http\Request;
use Xpressengine\Site\Site;

class ManagerController extends Controller
{
    public function __construct()
    {
        XePresenter::setSettingsSkinTargetId(Plugin::getId());
    }

    public function index()
    {
        return XePresenter::make('index', [
            'aaa' => 1,
        ]);
    }

    public function store(Request $request)
    {
        $commandLine = $request->get('commandLine');
        $arrCommands = explode(' ', $commandLine);
        $command = $commandLine;
        if (count($commandLine) > 0) {
            $command = array_shift($arrCommands);
        }

        $args = [];
        foreach ($arrCommands as $option) {
            $parts = explode('=', $option);
            if (count($parts) == 1) {
                $args[$parts[0]] = '';
            } else {
                $args[$parts[0]] = $parts[1];
            }
        }

        $queue = Queue::connection('rich_panel');
        $queue->push(function ($job) use ($command, $args) {
            app('xe.site')->setCurrentSite(Site::find('default'));

            // send email 'queue ready'
            $data = [
                'contents' => 'queue'
            ];
            $toMail = app('config')->get('mail.from.address');
            Mail::send('emails.notice', $data, function ($m) use ($toMail) {
                $fromEmail = app('config')->get('mail.from.address');
                $applicationName = 'XE3';

                $subject = 'Queue ready';

                $m->from($fromEmail, $applicationName);
                $m->to($toMail, 'System manager');
                $m->subject($subject);
            });

            Artisan::call($command, $args);

            // send email 'complete'
            Mail::send('emails.notice', $data, function ($m) use ($toMail) {
                $fromEmail = app('config')->get('mail.from.address');
                $applicationName = 'XE3';

                $subject = 'Queue Complete';

                $m->from($fromEmail, $applicationName);
                $m->to($toMail, 'System manager');
                $m->subject($subject);
            });

            $job->delete();
        });

        return redirect()->to(route('manage.rich_panel.index'));
    }
}
