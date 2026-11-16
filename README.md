# Personal Portfolio (HTML / CSS / PHP / JavaScript)

Futuristic dark portfolio with Three.js hero, Bento Grid, glassmorphism, kinetic typography and scroll effects.

## วิธีรัน (How to run)

**XAMPP:** คัดลอกโฟลเดอร์ `portfolio` ไปไว้ที่ `C:\xampp\htdocs\` → เปิด Apache → ไปที่ `http://localhost/portfolio/`

**PHP built-in server:**
```bash
cd portfolio
php -S localhost:8000
```
แล้วเปิด `http://localhost:8000` (ต้องใช้ PHP 7.4+)

> ต้องมีอินเทอร์เน็ต เพราะโหลด Google Fonts และ Three.js จาก CDN (ถ้าโหลด Three.js ไม่ได้ จะแสดงพื้นหลัง fallback แทน)

## แก้ข้อมูลส่วนตัว
แก้ที่ `config.php` ที่เดียว: ชื่อ, Student ID, มหาวิทยาลัย, Skills, Education, ช่องทางติดต่อ  
ค่าที่ยังเป็น `[YOUR NAME]`, `[STUDENT ID]`, `[UNIVERSITY]` คือ Placeholder ที่ต้องแก้

## ใส่รูปโปรไฟล์
วางไฟล์ที่ `assets/images/profile.jpg` (แนวตั้งสัดส่วน 4:5 สวยที่สุด) ไม่ต้องแก้ CSS ถ้าไม่มีไฟล์ จะแสดง "YOUR PHOTO"

## เพิ่ม Project
เปิด `data/projects.php` คัดลอก array 1 ก้อน เปลี่ยนค่า แล้วบันทึก การ์ดจะถูกสร้างอัตโนมัติ

| Field | ความหมาย |
|---|---|
| `id` | ลำดับ (แสดงเป็น Project 01, 02, ...) |
| `title`, `description`, `category` | ข้อความบนการ์ด |
| `image` | รูป Thumbnail เช่น `assets/images/projects/my-app.jpg` (ไม่มีไฟล์ = Placeholder) |
| `technologies` | รายการเทคโนโลยี |
| `github`, `demo` | ลิงก์จริง ถ้าว่างหรือ `#` ปุ่มจะถูกปิด |
| `size` | `''` ปกติ, `'wide'` กว้าง 2 ช่อง, `'feature'` ใหญ่ 2x2 |

## เพิ่ม Skill
เพิ่มแถวใน `skills` ของ `config.php` (icon ที่ใช้ได้ดูใน `includes/icons.php`)

## โครงสร้างไฟล์
```
portfolio/
├── index.php            # หน้าเว็บ (Template)
├── config.php           # ข้อมูลส่วนตัว / Skills / Education
├── data/projects.php    # รายการ Projects
├── includes/icons.php   # SVG icons
├── assets/css/style.css
├── assets/js/main.js
└── assets/images/       # profile.jpg, projects/
```
