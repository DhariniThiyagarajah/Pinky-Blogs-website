<?php

$pageTitle = "Pinky Blogs";

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';


$stmt = $conn->prepare(
    "SELECT 
        b.id,
        b.title,
        b.content,
        b.created_at,
        b.thumbnail,
        u.username
    FROM blogPost b
    JOIN user u ON b.user_id = u.id
    ORDER BY b.created_at DESC"
);

$stmt->execute();

$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();

?>


<style>

/* Pinky Blog Page */

body{

    background-color:#ffd6ef;

    background-image:
    radial-gradient(circle at 12% 8%, rgba(207,234,255,.75),transparent 40%),
    radial-gradient(circle at 88% 12%, rgba(200,182,255,.55),transparent 38%),
    radial-gradient(circle,rgba(255,255,255,.8) 1.5px,transparent 1.5px),
    linear-gradient(
        180deg,
        #ffe3f5,
        #ffd0ea
    );

    background-size:auto,auto,34px 34px,auto;

    font-family:'Quicksand',sans-serif;

}



/* floating container */

.blog-page{

    max-width:1100px;

    margin:40px auto;

    padding:25px;

    background:rgba(255,255,255,.55);

    backdrop-filter:blur(5px);

    border:3px solid #ff8fd0;

    border-radius:28px;

    box-shadow:
    0 15px 35px rgba(58,52,80,.15);

}



/* title */


.blog-heading{

    text-align:center;

    margin-bottom:35px;

}


.blog-heading h1{

    font-family:'Baloo 2',cursive;

    font-size:3rem;

    color:#5b476d;

    text-shadow:
    3px 3px white,
    0 5px 15px rgba(255,143,208,.4);

}


.blog-heading p{

    color:#7a6f95;

    font-weight:700;

}



/* blog grid */


.blog-container{

    display:grid;

    grid-template-columns:
    repeat(2,1fr);

    gap:25px;

}



/* card */


.blog-card{

    background:rgba(255,255,255,.75);

    border:5px solid #ffc3e8;

    border-radius:25px;

    padding:15px;

    box-shadow:
    0 8px 20px rgba(255,143,208,.25);

    transition:.3s;

}


.blog-card:hover{

    transform:translateY(-5px);

    border-color:#ff8fd0;

}



/* thumbnail */


.blog-thumb{

    width:100%;

    height:220px;

    border-radius:20px;

    overflow:hidden;

    border:3px solid #f6c6d8;

    background:#cfeaff;

    display:flex;

    align-items:center;

    justify-content:center;

}



.blog-thumb img{

    width:100%;

    height:100%;

    object-fit:cover;

}



.no-thumb{

    color:#8a7fa0;

    font-weight:700;

    text-align:center;

}



/* content */


.blog-content{

    padding:15px 5px;

}


.blog-content h2{

    font-family:'Baloo 2',cursive;

    color:#5b476d;

    font-size:1.5rem;

    margin-bottom:8px;

}


.blog-meta{

    font-size:.85rem;

    color:#8a7fa0;

    margin-bottom:12px;

    font-weight:600;

}


.blog-preview{

    color:#4a3f66;

    line-height:1.5;

}



/* button */


.read-btn{

    display:inline-block;

    margin-top:15px;

    padding:9px 20px;

    background:#ff8fd0;

    color:white;

    text-decoration:none;

    border-radius:20px;

    font-weight:700;

    transition:.2s;

}


.read-btn:hover{

    background:#c8b6ff;

}



/* empty */


.empty-blog{

    text-align:center;

    padding:40px;

    font-weight:700;

    color:#7a6f95;

}



/* mobile */


@media(max-width:800px){


.blog-page{

    margin:20px 12px;

    padding:15px;

}


.blog-heading h1{

    font-size:2.2rem;

}


.blog-container{

    grid-template-columns:1fr;

}


.blog-thumb{

    height:200px;

}


}


</style>




<div class="blog-page">


<div class="blog-heading">

<h1>
⋆｡°✩ Pinky Blogs ⋆｡°✩
</h1>

<p>
Read cozy stories, memories and creations from our community
</p>

</div>



<div class="blog-container">


<?php if(empty($blogs)): ?>


<div class="empty-blog">

No blogs available yet ♡

</div>


<?php else: ?>


<?php foreach($blogs as $blog): ?>


<div class="blog-card">



<div class="blog-thumb">


<?php if(!empty($blog['thumbnail'])): ?>


<img 
src="assets/blog-thumbnails/<?= htmlspecialchars($blog['thumbnail']) ?>"
alt="Blog thumbnail"
>


<?php else: ?>


<div class="no-thumb">

Add Blog Thumbnail

</div>


<?php endif; ?>


</div>




<div class="blog-content">


<h2>

<?= htmlspecialchars($blog['title']) ?>

</h2>



<div class="blog-meta">

✦ By <?= htmlspecialchars($blog['username']) ?>

<br>

✦ <?= htmlspecialchars(formatDate($blog['created_at'])) ?>

</div>




<p class="blog-preview">


<?= htmlspecialchars(
substr($blog['content'],0,150)
) ?>...


</p>




<a 
href="view.php?id=<?= $blog['id'] ?>"
class="read-btn">

Read More

</a>



</div>



</div>


<?php endforeach; ?>


<?php endif; ?>


</div>


</div>



<?php require_once __DIR__ . '/includes/footer.php'; ?>