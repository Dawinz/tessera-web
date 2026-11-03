<?php
/**
 * Dashonic export toolbar - Boxicons (same as Vehicle Report).
 * Shared across Tickets, Cargo, Accounts.
 */
if (!function_exists('sr_render_export_actions')) {
	function sr_render_export_actions(array $opts = []): void {
		$o = array_merge([
			'nav_class' => 'nav sr-ledger-export-actions flex-wrap gap-1 ms-lg-auto',
			'aria_label' => 'Export',
			'copy_id' => 'copy_table',
			'print_id' => 'print_table',
			'excel_id' => 'excel_table',
			'csv_id' => 'csv_table',
			'pdf_id' => 'pdf_table',
			'search_id' => 'search_row',
			'upload_id' => 'upload_account',
			'summary_id' => 'usersummary',
			'show_labels' => true,
			'always_labels' => false,
			'include_copy' => true,
			'include_print' => true,
			'include_excel' => true,
			'include_csv' => true,
			'include_pdf' => true,
			'include_search' => false,
			'include_upload' => false,
			'include_summary' => false,
		], $opts);

		$label = static function (string $text) use ($o): string {
			if (!$o['show_labels']) {
				return '';
			}
			if ($o['always_labels']) {
				return ' ' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
			}
			return '<span class="d-none d-md-inline"> ' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</span>';
		};

		$navClass = htmlspecialchars((string) $o['nav_class'], ENT_QUOTES, 'UTF-8');
		$aria = htmlspecialchars((string) $o['aria_label'], ENT_QUOTES, 'UTF-8');
		?>
<nav class="<?php echo $navClass; ?>" aria-label="<?php echo $aria; ?>">
		<?php if ($o['include_copy']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['copy_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Copy"><i class="bx bx-copy"></i><?php echo $label('Copy'); ?></a>
		<?php endif; ?>
		<?php if ($o['include_print']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['print_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Print"><i class="bx bx-printer"></i><?php echo $label('Print'); ?></a>
		<?php endif; ?>
		<?php if ($o['include_excel']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['excel_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Excel"><i class="bx bx-export"></i><?php echo $label('Excel'); ?></a>
		<?php endif; ?>
		<?php if ($o['include_csv']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['csv_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="CSV"><i class="bx bx-file"></i><?php echo $label('CSV'); ?></a>
		<?php endif; ?>
		<?php if ($o['include_pdf']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['pdf_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="PDF"><i class="bx bx-file-blank"></i><?php echo $label('PDF'); ?></a>
		<?php endif; ?>
		<?php if ($o['include_search']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['search_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Search"><i class="bx bx-search"></i></a>
		<?php endif; ?>
		<?php if ($o['include_upload']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['upload_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Upload"><i class="bx bx-upload"></i></a>
		<?php endif; ?>
		<?php if ($o['include_summary']): ?>
	<a id="<?php echo htmlspecialchars((string) $o['summary_id'], ENT_QUOTES, 'UTF-8'); ?>" class="nav-link" href="#" title="Summary"><i class="bx bx-list-ul"></i></a>
		<?php endif; ?>
</nav>
		<?php
	}
}
