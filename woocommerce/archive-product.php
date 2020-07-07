<?php get_header(); ?>

<?php

$products =[];
if(have_posts()) { while(have_posts()){ the_post();
  $products[] = wc_get_product(get_the_ID());
}}
$data =[];

$data['products'] = format_products($products);

?>
<div class="container breadcrumb">
  <?php woocommerce_breadcrumb( ['delimiter' => ' > '] );?>
</div>

<article class="container products-archive">
  <nav class="filtros">
    <h2>Filtros</h2>
    <div class="filtro">
      <h3 class="filtro-titulo">Categorias</h3>
      <?php wp_nav_menu([
        'menu' => 'categorias-internas',
        'menu_class' => 'filtro-cat',
        'container' => false
      ]);
      ?>
    </div>
    <div class="filtro">
        <?php 
          $attribute_taxonomies = wc_get_attribute_taxonomies();
          foreach($attribute_taxonomies as $attribute) {
            the_widget('WC_Widget_Layered_Nav', [
              'title' => $attribute->attribute_label,
              'attribute' => $attribute->attribute_name,
            ]);
          }
        ?>
    </div>
    <div class="filtro">
      <h3 class="filtro-titulo">Filtrar Por Preço</h3>
      <form action="" class="filtro-preco">
        <div>
          <label for="min_price">De R$</label>
          <input type="text" required name="min_price" id="min_price" value="<?php $_GET['min_price']?>">
        </div>
        <div>
          <label for="max_price">Até R$</label>
          <input type="text" required name="max_price" id="max_price" value="<?php $_GET['max_price']?>">
        </div>
        <button type="submit">Filtrar</button>
      </form>
    </div>
  </nav>
  <main>
    <?php if($data['products'][0]) {?>
      <?php woocommerce_catalog_ordering(); ?>
      <?php handel_product_list($data['products']);?>
      <?= get_the_posts_pagination(); ?>
    <?php } else { ?>
      <p>Nenhum resoltado para sua busca.</p>
    <?php }?>

  </main>
</article>

<?php get_footer(); ?>