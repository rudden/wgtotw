<h1 class='content-subhead'>Manage users</h1>

<span class='fa fa-align-justify'></span><a href='<?=$this->url->create('users/list')?>'> Show all users</a><br />
<span class='fa fa-users'></span><a href='<?=$this->url->create('users/active')?>'> Show active users</a><br />
<span class='fa fa-users'></span><a href='<?=$this->url->create('users/inactive')?>'> Show inactive users</a><br />
<span class='fa fa-user-plus'></span><a href='<?=$this->url->create('users/add')?>'> Create a new user</a><br />
<span class='fa fa-trash-o'></span><a href='<?=$this->url->create('users/trash')?>'> Garbage bin</a>