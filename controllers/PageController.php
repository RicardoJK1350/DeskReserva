<?php

class PageController
{
    
    public function index()
    {
        $rotaRaw = isset($_GET['route']) ? $_GET['route'] : 'home';

        $slug = basename($rotaRaw);

        $arquivoView = VIEWS_PATH . '/' . $slug . '.php';

        if (file_exists($arquivoView)) {
            render_view($slug);
        } else {
            render_view('404', ['slug' => $slug]);
        }
    }
}
