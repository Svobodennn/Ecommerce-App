
# Ecommerce Sipariş Yönetimi Projesi

Proje tamamlanmıştır. Projenin kurulumuyla birlikte gerekli veritabanı ve tablolar içlerindeki bilgilerle birlikte oluşturulur. Veritabanı kullanıcı adı "root", ve şifresi yoktur.




## Özellikler

- Giriş ve kayıt işlemleri
- Ürünleri sepete ekleme, çıkarma ve sepetten kaldırma
- Kupon kodu kontrolü
- Kupon kodu girilmişse sepet tutarına göre yüzdelik indirimler
- Sepet tutarına göre kargo ücreti tahsil edilmesi
- Kullanıcının tüm siparişlerini görüntüleyebilmesi ve dekont yazdırabilmesi


## Kurulum

Projeyi kurmak için xampp/htdocs klasörüne gidin ve komut satırında şu komutu çalıştırın:

```as
  git clone https://github.com/Svobodennn/Ecommerce-App.git
```
Proje başarıyla clone edildikten sonra bu komutu çalıştırarak gerekli özellikleri yükleyin:
```as
  cd Ecommerce-App
  composer install
```
Xampp'ı çalıştırdıktan sonra
```as
  http://localhost/Ecommerce-App
```
Adresine gidin.
## Bilinen hatalar

- Sayfa yenilendiğinde sepete ilk eklenen ürün sepette gözükmüyor (Sadece görsel olarak,cookie'ye ekleniyor.)
- Çok fazla istek gönderilirse PHP Mailer çalışmıyor.

## Kullanılan teknolojiler

- [Composer](https://getcomposer.org/)
- [Sweetalert2](https://github.com/sweetalert2/sweetalert2)
- [Toastr](https://github.com/CodeSeven/toastr)
- [JQuery](https://jquery.com/)
- [PHP Mailer](https://github.com/PHPMailer/PHPMailer)
- [Bramus Router](https://github.com/bramus/router)
- [Tema - AdminLte](https://adminlte.io/)


