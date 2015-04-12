<?php namespace Neomerx\CoreApi\Events;

use \Neomerx\Core\Support as S;
use \Illuminate\Support\Facades\Config;
use \Illuminate\Support\Facades\Event as EventFacade;

class Event
{
    /**
     * Config file key.
     */
    const CONFIG_KEY_EVENT_HANDLERS = 'nm::events.handlers';

    /**
     * Fire event.
     *
     * @param EventArgs $args
     */
    public static function fire(EventArgs $args)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        EventFacade::fire($args->getName(), [$args]);
    }

    /**
     * Register event handlers specified in config.
     */
    public static function setupHandlers()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $handlers = Config::get(self::CONFIG_KEY_EVENT_HANDLERS);
        foreach ($handlers as $handlerInfo) {
            $event    = S\arrayGetValue($handlerInfo, 'event');
            $handler  = S\arrayGetValue($handlerInfo, 'handler');
            $priority = S\arrayGetValue($handlerInfo, 'priority', 0);

            /** @noinspection PhpUndefinedMethodInspection */
            EventFacade::listen($event, $handler, $priority);
        }
    }
}
