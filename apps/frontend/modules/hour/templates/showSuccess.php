
<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $hour->getid() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $hour->getuser_id() ?></td>
    </tr>
    <tr>
      <th>Time:</th>
      <td><?php echo $hour->gettime() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $hour->getcreated_at() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $hour->getupdated_at() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('hour/edit?id='.$hour->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('hour/index') ?>">List</a>
