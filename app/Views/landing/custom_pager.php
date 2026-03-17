<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */

$pager->setSurroundCount(2);
?>

<nav aria-label="">
	<ul class="pagination">
		<?php if ($pager->hasPrevious()) : ?>
			<li>
				<a class="badge text-bg-success" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
					<span aria-hidden="true"><?= lang('Pager.first') ?></span>
				</a>
			</li>&nbsp;
			<li>
				<a class="badge text-bg-success" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
					<span aria-hidden="true"><?= lang('Pager.previous') ?></span>
				</a>
			</li>&nbsp;
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li  <?= $link['active'] ? 'class="active"' : '' ?>>
				<a href="<?= $link['uri'] ?>" class="badge text-bg-<?= $link['active'] ? 'warning' : 'success' ?>">
					<?= $link['title'] ?>
				</a>
			</li>&nbsp;
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li>
				<a class="badge text-bg-success" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
					<span aria-hidden="true"><?= lang('Pager.next') ?></span>
				</a>
			</li>&nbsp;
			<li>
				<a class="badge text-bg-success" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
					<span aria-hidden="true"><?= lang('Pager.last') ?></span>
				</a>
			</li>&nbsp;
		<?php endif ?>
	</ul>
</nav>