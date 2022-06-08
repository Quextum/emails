<?php declare(strict_types=1);

namespace Quextum\Emails;


use Latte\Runtime\Filters;
use Nette\Mail\Message;
use Nette\Utils\Html;
use Tracy\Debugger;
use Tracy\IBarPanel;

class EmailDebugPanel implements IBarPanel
{


	public Html $panel;

	public function __construct(Message $message)
	{

		$header = Html::el('div', ['class' => 'debugger-email-panel-headers panel-head']);
		$header->addHtml(Debugger::dump($message, true));
		$src = $message->getHtmlBody();
		$src = Filters::spacelessHtml($src);
        $src = Html::fromHtml($src);
		$iframe = Html::el('iframe', [
				'src' => 'data:text/html,' . $src,
				'srcdoc' => $src,
				'style' => 'resize: both;min-height: 700px; min-width: 700px;',
				'class' => 'debugger-email-panel-body panel-body resizable ui-resizable border w-100']
		);
		$html = "<div class='debugger-email-panel panel panel-default'><table><tbody><tr><td style='min-width: 400px;'>$header</td><td>Preview$iframe</td></tr></tbody></table></div>";
		$this->panel = Html::fromHtml($html);
	}

	public function getTab():Html
	{
		return Html::el('span', ['title' => 'Emails'])
			->addHtml(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'mail.svg'));
	}

	public function getPanel():Html
	{
		return $this->panel;
	}

}
