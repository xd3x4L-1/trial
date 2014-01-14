<h1>Index Controller</h1>
<p>Welcome to Trial index controller.</p>

<h2>Download</h2>
<p>You can download Trial from github.</p>
<blockquote>
<code>git clone git://github.com/xd3x4L-1/trial.git</code>
</blockquote>
<p>You can review its source directly on github: <a href='https://github.com/xd3x4L-1/trial'>https://github.com/xd3x4L-1/trial</a></p>

<h2>Installation</h2>
<p>First you have to make the data-directory writable. This is the place where Trial needs
to be able to write and create files.<br></br>
some parts of the page are using less and themes/grid. Trial needs
to be able to write in themes/grid. </p>
<blockquote>
<code>cd Trial; chmod 777 site/data</code><br></br>
<code>cd Trial; chmod 777 themes/grid</code>
</blockquote>

<p>Second, Trial has some modules that need to be initialised. You can do this through a
controller. Point your browser to the following link.</p>
<blockquote>
<a href='<?=create_url('module/install')?>'>module/install</a>
</blockquote>