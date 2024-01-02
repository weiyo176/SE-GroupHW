1. 從phpMyAdmin登入至MySQL。

2. 新增使用者110213065(密碼:z2112240),並產生同名之資料庫110213065
      * 帳號: "使用文字方塊:" 110213065
	  * 主機名稱： 選"本機" (localhost)
	  * 勾選 "建立與使用者同名的資料庫並授予所有權限。"

3. 於110213065 database新增一資料表110213065
  CREATE TABLE users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE, --對應帳號
  password VARCHAR(60) NOT NULL, --對應密碼
  interest VARCHAR(100) NOT NULL, -- 新增興趣欄位
  expertise VARCHAR(100) NOT NULL -- 新增專長欄位
)
   
4. 至log1.php點選尚未註冊帳號並新增一帳號(會跳到register.html，username帳號我有規定要用email才能註冊)，若密碼確認輸入不一致
會跳到register.php並顯示密碼不一致，請按上一頁跳回register.html並按重新整理方可繼續註冊。

5. 註冊完後請跳出去，再次進入log1.php，並輸入剛剛註冊的帳密，若輸入錯誤，則網址後面會顯示login1.php?error=1。
若輸入成功則會顯示會員資料

6.可更改會員資料有興趣和專長和帳號，按下更新資料後資料庫的資料重整後也會更新。
(若該帳號已有人註冊則網址顯示member_profile.php?error=2)

7.最後是更新密碼，要舊帳號正確、新密碼與確認新密碼一致才能更新。

8.更新完密碼或帳號之後可以再登入試試看，要用新的才登的進去。

備註:帳號規定為電子信箱是順便當作通訊資料。

暑假聯絡方式(信箱):s110213065@mail1.ncnu.edu.tw
助教如有疑問請聯絡，辛苦了!!!