<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>>
      <a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    print render($content);
    ?>
    <div class="row">

      <div class="col-md-4">
        <h3>Server Actions</h3>

        <div>
          <?php print render($actions_form); ?>
        </div>
      </div>

      <div class="col-md-8">
        <h3>Server Status</h3>

        <section class="xs-vm-section">
          <p>

          <div class="xs-vm-row">
            <label class="display-inline">Power state</label>:
            <span class="vm-config-value"><?php print $vm_power_state; ?></span>
            <span class="vm-config-icon vm-config-icon-<?php print $vm_power_state_class; ?>">&nbsp;</span>
          </div>

          <div class="xs-vm-row">
            <label class="display-inline">CPUs</label>
            <span class="vm-config-value xs-vm-tag"><?php print $vm_cpu; ?></span>
            <label class="display-inline">RAM</label>
            <span class="vm-config-value xs-vm-tag"><?php print $vm_memory; ?>GB</span>
          </div>

          <div class="xs-vm-row">
            <label class="display-inline">Start time</label>:
            <span class="vm-config-value"><?php print $start_time; ?></span>
          </div>

          <div class="xs-vm-row">
            <label class="display-inline">Server uptime</label>:
            <span class="vm-config-value"><?php print $server_uptime; ?></span>
          </div>

        </section>
      </div>
      <div class="col-md-12">
        <h3>Server Configuration</h3>
        <section class="xs-vm-section">
          <div class="xs-vm-row">
            <label class="display-inline">Network cards</label>:
            <span class="vm-config-value"><?php print $vm_nics; ?></span>
          </div>

          <?php if (!empty($vm_vifs)) : ?>
            <?php foreach ($vm_vifs as $delta => $vif) : ?>
              <div class="xs-vm-row">
                <label class="display-inline">Network Card <?php print $delta; ?></label>
                <ul>
                  <li>
                    <label class="display-inline">Device Name</label>: <?php print $vif['device']; ?>
                  </li>
                  <li>
                    <label class="display-inline">MAC Address</label>: <?php print $vif['mac']; ?>
                  </li>
                  <?php if (!empty($network['ips'])) : ?>
                    <li>
                      <label class="display-inline">IP Address</label>: <?php print !empty($network['ips'][$delta]) ? $network['ips'][$delta] : 'Unconfigured'; ?>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <div class="xs-vm-row">
            <label class="display-inline">Server Name</label>:
            <span class="description"><?php print $vm_name; ?></span>
          </div>

          <div class="xs-vm-row">
            <label class="display-inline">Server Description</label>:
            <span class="description"><?php print $vm_description; ?></span>
          </div>
          </p>

        </section>
      </div>

      <div class="col-md-12">
        <h3>Operating System</h3>
        <section class="xs-vm-section">

          <ul>
            <?php if (!empty($os['version'])) : ?>
              <li>
                <label class="display-inline">Operating System</label>: <?php print $os['version']; ?>
              </li>
            <?php endif; ?>

            <?php if (!empty($os['kernel'])) : ?>
              <li>
                <label class="display-inline">Kernel</label>:<?php print $os['kernel']; ?>
              </li>
            <?php endif; ?>

            <?php if (!empty($os['distro'])) : ?>
              <li>
                <label class="display-inline">Distro</label>: <?php print $os['distro']; ?>
              </li>
            <?php endif; ?>

            <?php if (!empty($os['major'])) : ?>
              <li>
                <label class="display-inline">OS Major version</label>: <?php print $os['major']; ?>
              </li>
            <?php endif; ?>

            <?php if (!empty($os['minor'])) : ?>
              <li>
                <label class="display-inline">OS Minor version</label>: <?php print $os['minor']; ?>
              </li>
            <?php endif; ?>
          </ul>
        </section>
      </div>
    </div>

  </div>
</div>