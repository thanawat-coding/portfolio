<?php
/**
 * data/projects.php — เพิ่มผลงานโดยคัดลอก array 1 ก้อน แล้วแก้ค่า ไม่ต้องแก้ HTML
 * To add a project: copy one block and change the values.
 *
 * image         : path ของรูป (ถ้าไม่มีไฟล์ จะแสดง Placeholder อัตโนมัติ)
 * github / demo : ใส่ '' หรือ '#' ถ้ายังไม่มีลิงก์ — ปุ่มจะถูกปิด ไม่ลิงก์ปลอม
 * size          : '' ปกติ | 'wide' กว้าง 2 ช่อง | 'feature' ใหญ่ 2x2
 */
return [
    ['id' => 1, 'title' => '[PROJECT NAME 1]', 'category' => 'Web',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-01.jpg',
     'technologies' => ['HTML', 'CSS', 'JavaScript'], 'github' => '#', 'demo' => '#', 'size' => 'feature'],
    ['id' => 2, 'title' => '[PROJECT NAME 2]', 'category' => 'Mobile',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-02.jpg',
     'technologies' => ['Flutter', 'Dart'], 'github' => '#', 'demo' => '#', 'size' => ''],
    ['id' => 3, 'title' => '[PROJECT NAME 3]', 'category' => 'Backend',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-03.jpg',
     'technologies' => ['PHP', 'MySQL'], 'github' => '#', 'demo' => '#', 'size' => ''],
    ['id' => 4, 'title' => '[PROJECT NAME 4]', 'category' => 'Systems',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-04.jpg',
     'technologies' => ['Python', 'WebSocket'], 'github' => '#', 'demo' => '#', 'size' => 'wide'],
    ['id' => 5, 'title' => '[PROJECT NAME 5]', 'category' => 'Web',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-05.jpg',
     'technologies' => ['JavaScript'], 'github' => '#', 'demo' => '#', 'size' => ''],
    ['id' => 6, 'title' => '[PROJECT NAME 6]', 'category' => 'Design',
     'description' => '[Describe what this project does and what you built.]',
     'image' => 'assets/images/projects/project-06.jpg',
     'technologies' => ['Figma', 'CSS'], 'github' => '#', 'demo' => '#', 'size' => ''],
];
