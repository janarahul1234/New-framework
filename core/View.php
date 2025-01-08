<?php

namespace Core;

use eftec\bladeone\BladeOne;

class View
{
    private BladeOne $blade;

    public function __construct()
    {
        $views = ROOT_DIR . '/app/views';
        $cache = sys_get_temp_dir();

        $mode = env('APP_DEBUG', false) ? BladeOne::MODE_DEBUG : BladeOne::MODE_AUTO;
        $this->blade = new BladeOne($views, $cache, $mode);

        $this->blade->setBaseUrl(env('APP_URL', 'http://localhost:8000'));
        $this->customFunctions();
    }

    public function render(string $template, array $data = []): string
    {
        return $this->blade->run($template, $data);
    }

    private function customFunctions(): void
    {
        $this->blade->directive('route', function ($args) {
            return "<?php echo route({$args}); ?>";
        });
    }
}

// EOF
