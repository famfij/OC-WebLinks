<?php
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;

// Home page
$app->get('/', function () use ($app) {
    $links = $app['dao.link']->findAll();
    return $app['twig']->render('index.html.twig', array('links' => $links));
})->bind('home');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

// link submit page
$app->match('/link/submit', function(Request $request) use ($app) {
    $linkFormView = null;
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        $link = new Link();
        $user = $app['user'];
        $link->setUser($user);
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $app['dao.link']->save($link);
            $app['session']->getFlashBag()->add('success', 'Your link was succesfully added.');
        }
        $linkFormView = $linkForm->createView();
    }
    return $app['twig']->render('link_form.html.twig', array('linkForm'=>$linkFormView));
})->bind('link_submit');

// admin page
$app->get('/admin', function() use ($app) {
    $links = $app['dao.link']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin.html.twig', array('links'=>$links, 'users'=>$users));
})->bind('admin');

// api links
$app->get('/api/links', function() use ($app) {
    $links = $app['dao.link']->findAll();
    $responseData = array();
    foreach ($links as $link) {
        $linkdata = array();
        $linkdata['id'] = $link->getId();
        $linkdata['title'] = $link->getTitle();
        $linkdata['url'] = $link->getUrl();
        $user = $link->getUser();
        $linkdata['user'] = array('id'=>$user->getId(), 'name'=>$user->getUsername());

        $responseData[] = $linkdata;
    }
    return $app->json($responseData);
});

// api link
$app->get('/api/link/{id}', function($id) use ($app) {
    try {
        $link = $app['dao.link']->find($id);

        $responseData = array();
        $responseData['id'] = $link->getId();
        $responseData['title'] = $link->getTitle();
        $responseData['url'] = $link->getUrl();
        $user = $link->getUser();
        $responseData['user'] = array('id'=>$user->getId(), 'name'=>$user->getUsername());

        return $app->json($responseData);
    } catch (\Exception $e) {
        return $app->json('No content', 204);
    }
});
