<h1>Hour List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Time</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($hour_list as $hour): ?>
    <tr>
      <td><a href="<?php echo url_for('hour/show?id='.$hour->getId()) ?>"><?php echo $hour->getId() ?></a></td>
      <td><?php echo $hour->getUserId() ?></td>
      <td><?php echo $hour->getTime() ?></td>
      <td><?php echo $hour->getCreatedAt() ?></td>
      <td><?php echo $hour->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('hour/new') ?>">New</a>
