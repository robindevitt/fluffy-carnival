<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fluffy Carnical</title>
    <?php wp_head();?>
</head>
    <body>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
            <?php the_content(); ?>
        <?php endwhile; endif; ?>
    </body>
<?php wp_foot();?>
</html>