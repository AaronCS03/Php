
# 📘 Guía para Principiantes: Cómo Crear Reportes en C# Paso a Paso

Bienvenido a esta guía completa donde te enseñaremos cómo crear reportes en **C#**. Esta guía está pensada para principiantes que **no tienen experiencia previa**, por lo que explicaremos **todo desde cero**, paso a paso.
Un **reporte** es un documento que nos muestra datos organizados, como los detalles de una **factura**, los **productos vendidos**, los **clientes atendidos**, etc. Sirve para mostrar, imprimir o guardar información importante de forma clara y ordenada.

---

## 🧩 Parte 1: Crear Reportes en C# con SQL Server

### ✅ ¿Qué necesitas?

- Visual Studio instalado.
- SQL Server Management Studio (SSMS).
- Conocimientos básicos de cómo crear un formulario en Windows Forms.
- Entity Framework.
- Componente `ReportViewer`.

### 🧱 Paso 1: Crear la base de datos

Abre SQL Server Management Studio y ejecuta este código:

```sql
CREATE DATABASE BD_FacturacionPruebas;

USE BD_FacturacionPruebas;

CREATE TABLE Factura (
    ID INT PRIMARY KEY IDENTITY,
    Descripcion VARCHAR(100),
    Categoria VARCHAR(50),
    Cantidad INT,
    Precio_Unitario DECIMAL(10,2),
    ITEBIS DECIMAL(10,2),
    Descuento DECIMAL(10,2),
    Total_General DECIMAL(10,2)
);
```

### 🧱 Paso 2: Crear el proyecto en Visual Studio

1. Abre Visual Studio.
2. Crea un proyecto tipo **Windows Forms App (.NET Framework)**.
3. Agrega Entity Framework desde NuGet:
   - Haz clic derecho en el proyecto → **Administrar paquetes NuGet**.
   - Busca **EntityFramework** e instálalo.

### 🧱 Paso 3: Agregar la conexión a la base de datos

1. Agrega un archivo `App.config` (si no lo tienes).
2. Coloca esta línea dentro de `<configuration>`:

```xml
<connectionStrings>
  <add name="conexion" connectionString="Data Source=.;Initial Catalog=BD_FacturacionPruebas;Integrated Security=True" providerName="System.Data.SqlClient" />
</connectionStrings>
```

### 🧱 Paso 4: Crear un Modelo de Datos

1. Agrega una clase llamada `Factura`.
2. Define las propiedades que coincidan con las columnas de la tabla.

```csharp
public class Factura
{
    public int ID { get; set; }
    public string Descripcion { get; set; }
    public string Categoria { get; set; }
    public int Cantidad { get; set; }
    public decimal Precio_Unitario { get; set; }
    public decimal ITEBIS { get; set; }
    public decimal Descuento { get; set; }
    public decimal Total_General { get; set; }
}
```

3. Agrega una clase de contexto `FacturaContext`:

```csharp
using System.Data.Entity;

public class FacturaContext : DbContext
{
    public FacturaContext() : base("name=conexion") { }
    public DbSet<Factura> Facturas { get; set; }
}
```

### 🧱 Paso 5: Crear el Reporte

1. Haz clic derecho en el proyecto → **Agregar → Nuevo elemento → Reporte (.rdlc)**.
2. Diseña una tabla con los campos de la factura.
3. Agrega un formulario y un componente **ReportViewer** desde la caja de herramientas.
4. En el código del formulario, carga los datos:

```csharp
FacturaContext db = new FacturaContext();
var lista = db.Facturas.ToList();
ReportDataSource rds = new ReportDataSource("DataSet1", lista);
reportViewer1.LocalReport.DataSources.Clear();
reportViewer1.LocalReport.DataSources.Add(rds);
reportViewer1.RefreshReport();
```
# 📘 Documentación para Proyecto de Reportes en C#

Esta guía está diseñada para principiantes que desean aprender cómo crear un sistema de facturación con generación de reportes en PDF utilizando C#, SQL Server y ReportViewer.

---

## 📁 Estructura del Proyecto

Asegúrate de que tu solución tenga la siguiente estructura de carpetas y archivos:

```
ReportesProyecto/
├── Properties/
├── Referencias/
├── Reportes/
│   ├── DataSet.xsd
│   ├── Reporte1.rdlc
│   └── ReporteForm.cs
├── SqlServerTypes/
├── ActualizarFactura.cs
├── AgregarFactura.cs
├── App.config
├── Conexion.cs
├── Form1.cs
├── packages.config
└── Program.cs
```

---

## 🧱 Paso 1: Crear la conexión a la base de datos (Conexion.cs)

Este archivo permite que el proyecto se comunique con SQL Server.

📍**Ubicación del código:** Archivo `Conexion.cs`

```csharp
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlClient;
using System.Data;

namespace ReportesProyecto
{
    public class Conexion
    {

        private static string stringConnection = "Data Source=AARONCS;Initial Catalog=BD_FacturacionPruebas;Integrated Security=True;";

        public static void AgregarFactura(string Descripcion, string Categoria, int Cantidad, decimal Precio_Unitario, decimal Itebis, decimal Descuento, decimal Total_General)
        {
            using (SqlConnection conn = new SqlConnection(stringConnection))
            {
                conn.Open();
   
                SqlCommand cmd = new SqlCommand("INSERT INTO Facturas(Descripcion, Categoria, Cantidad, Precio_Unitario, Itebis, Descuento, Total_General) " +
                    "VALUES (@Descripcion, @Categoria, @Cantidad, @Precio_Unitario, @Itebis, @Descuento, @Total_General)", conn);

                cmd.Parameters.AddWithValue("@Descripcion", Descripcion);
                cmd.Parameters.AddWithValue("@Categoria", Categoria);
                cmd.Parameters.AddWithValue("@Cantidad", Cantidad);
                cmd.Parameters.AddWithValue("@Precio_Unitario", Precio_Unitario);
                cmd.Parameters.AddWithValue("@Itebis", Itebis);
                cmd.Parameters.AddWithValue("@Descuento", Descuento);
                cmd.Parameters.AddWithValue("@Total_General", Total_General);

                cmd.ExecuteNonQuery();
            }
        }

        public static void Eliminar(int id)
        {
            using (SqlConnection conn = new SqlConnection(stringConnection))
            {
                conn.Open();

                SqlCommand cmd = new SqlCommand("DELETE FROM Facturas WHERE ID = @id", conn);
                cmd.Parameters.AddWithValue("@id", id);
                cmd.ExecuteNonQuery();
            }
        }

        public static void ActualizarFactura(int id, string Descripcion, string Categoria, int Cantidad, decimal Precio_Unitario, decimal Itebis, decimal Descuento, decimal Total_General)
        {
            using (SqlConnection conn = new SqlConnection(stringConnection))
            {
                conn.Open();

                SqlCommand cmd = new SqlCommand("UPDATE Facturas SET Descripcion = @Descripcion, Categoria = @Categoria, Cantidad = @Cantidad, " +
                    "Precio_Unitario = @Precio_Unitario, Itebis = @Itebis, Descuento = @Descuento, Total_General = @Total_General WHERE ID = @id", conn);

                cmd.Parameters.AddWithValue("@id", id);
                cmd.Parameters.AddWithValue("@Descripcion", Descripcion);
                cmd.Parameters.AddWithValue("@Categoria", Categoria);
                cmd.Parameters.AddWithValue("@Cantidad", Cantidad);
                cmd.Parameters.AddWithValue("@Precio_Unitario", Precio_Unitario);
                cmd.Parameters.AddWithValue("@Itebis", Itebis);
                cmd.Parameters.AddWithValue("@Descuento", Descuento);
                cmd.Parameters.AddWithValue("@Total_General", Total_General);

                cmd.ExecuteNonQuery();
            }
        }

        public static DataSet LeerFacturas()
        {
            using (SqlConnection conn = new SqlConnection(stringConnection))
            {
                conn.Open();

                SqlCommand cmd = new SqlCommand("SELECT * FROM Facturas", conn);

                SqlDataAdapter adapter = new SqlDataAdapter(cmd);
                DataSet ds = new DataSet();
                adapter.Fill(ds);

                return ds;
            }
        }
    }
}

```

---

## 🧾 Paso 2: Crear formulario para insertar facturas (AgregarFactura.cs)

Este formulario contendrá campos como descripción, cantidad, precio, ITBIS, descuento y total.

📍**Ubicación del código:** Archivo `AgregarFactura.cs`

```csharp
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ReportesProyecto
{
    public partial class AgregarFactura : Form
    {
        public AgregarFactura()
        {
            InitializeComponent();
        }

        private void agregarBtn_Click(object sender, EventArgs e)
        {
            string descripcion = descripcionBox.Text.Trim();
            string categoria = categoriaBox.Text.Trim();
            string cantidadText = cantidadBox.Text.Trim();
            string precioUnitarioText = precioUnitarioBox.Text.Trim();
            string itbisText = itbisBox.Text.Trim();
            string descuentoText = descuentoBox.Text.Trim();
            string totalGeneralText = totalGeneralBox.Text.Trim();

            if (string.IsNullOrEmpty(descripcion) || string.IsNullOrEmpty(categoria) ||
                string.IsNullOrEmpty(cantidadText) || string.IsNullOrEmpty(precioUnitarioText) ||
                string.IsNullOrEmpty(itbisText) || string.IsNullOrEmpty(descuentoText) ||
                string.IsNullOrEmpty(totalGeneralText))
            {
                MessageBox.Show("Todos los campos son obligatorios.", "Advertencia", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            if (!int.TryParse(cantidadText, out int cantidad))
            {
                MessageBox.Show("La cantidad debe ser un número entero válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(precioUnitarioText, out decimal precioUnitario))
            {
                MessageBox.Show("El precio unitario debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(itbisText, out decimal itbis))
            {
                MessageBox.Show("El ITBIS debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(descuentoText, out decimal descuento))
            {
                MessageBox.Show("El descuento debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(totalGeneralText, out decimal totalGeneral))
            {
                MessageBox.Show("El total general debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            try
            {
                Conexion.AgregarFactura(descripcion, categoria, cantidad, precioUnitario, itbis, descuento, totalGeneral);
                MessageBox.Show("Factura agregada correctamente.", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Ocurrió un error al agregar la factura: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }
    }
}

```

Este formulario debe tener dos botones: `Guardar Factura` y `Registro`.

---

## 📝 Paso 3: Crear formulario principal (Form1.cs)

Este formulario debe tener botones para:

- Insertar Factura
- Editar Factura
- Eliminar Factura
- Imprimir Factura

📍**Ubicación del código:** Archivo `Form1.cs`

```csharp
using ReportesProyecto.Reportes;
using System;
using System.Windows.Forms;

namespace ReportesProyecto
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void RefreshData()
        {
            dataGridView1.DataSource = Conexion.LeerFacturas();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            RefreshData();
        }

        private void agregarBtn_Click(object sender, EventArgs e)
        {
            AgregarFactura form = new AgregarFactura();
            form.ShowDialog();
            RefreshData();
        }

        private void actualizarBtn_Click(object sender, EventArgs e)
        {
            if (dataGridView1.SelectedRows.Count == 0)
            {
                MessageBox.Show("Por favor seleccione una fila.", "Advertencia", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var selectedRow = dataGridView1.SelectedRows[0];

            try
            {

                int id = Convert.ToInt32(selectedRow.Cells["ID"].Value?.ToString().Trim());
                string descripcion = selectedRow.Cells["Descripcion"].Value?.ToString().Trim();
                string categoria = selectedRow.Cells["Categoria"].Value?.ToString().Trim();
                int cantidad = Convert.ToInt32(selectedRow.Cells["Cantidad"].Value);
                decimal precioUnitario = Convert.ToDecimal(selectedRow.Cells["Precio_Unitario"].Value);
                decimal itbis = Convert.ToDecimal(selectedRow.Cells["Itebis"].Value);
                decimal descuento = Convert.ToDecimal(selectedRow.Cells["Descuento"].Value);
                decimal totalGeneral = Convert.ToDecimal(selectedRow.Cells["Total_General"].Value);

                ActualizarFactura detalleForm = new ActualizarFactura(id, descripcion, categoria, cantidad, precioUnitario, itbis, descuento, totalGeneral);
                detalleForm.ShowDialog();
                RefreshData();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Error al obtener los datos de la fila seleccionada: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void eliminarBtn_Click(object sender, EventArgs e)
        {
            if (dataGridView1.SelectedRows.Count == 0)
            {
                MessageBox.Show("Por favor seleccione una fila.", "Advertencia", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            var selectedRow = dataGridView1.SelectedRows[0];

            try
            {
                int id = Convert.ToInt32(selectedRow.Cells["ID"].Value?.ToString());
                Conexion.Eliminar(id);
                RefreshData();
            }catch(Exception ex)
            {
                MessageBox.Show($"Error al obtener el identificador de la fila seleccionada: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void reporteBtn_Click(object sender, EventArgs e)
        {
            ReporteForm form = new ReporteForm();
            form.ShowDialog();
        }
    }
}

```

---

## 🛠️ Paso 4: Crear el formulario para actualizar facturas (ActualizarFactura.cs)

Permite editar los datos de una factura existente.

📍**Ubicación del código:** Archivo `ActualizarFactura.cs`

```csharp
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ReportesProyecto
{
    public partial class ActualizarFactura : Form
    {
        public int Id;

        public ActualizarFactura(int id, string descripcion, string categoria, int cantidad, decimal precioUnitario, decimal itbis, decimal descuento, decimal totalGeneral)
        {
            InitializeComponent();
            Id = id;
            descripcionBox.Text = descripcion;
            categoriaBox.Text = categoria;
            cantidadBox.Text = cantidad.ToString();
            precioUnitarioBox.Text = precioUnitario.ToString("0.00");
            itbisBox.Text = itbis.ToString("0.00");
            descuentoBox.Text = descuento.ToString("0.00");
            totalGeneralBox.Text = totalGeneral.ToString("0.00");
        }

        private void actualizarBtn_Click(object sender, EventArgs e)
        {
            string descripcion = descripcionBox.Text.Trim();
            string categoria = categoriaBox.Text.Trim();
            string cantidadText = cantidadBox.Text.Trim();
            string precioUnitarioText = precioUnitarioBox.Text.Trim();
            string itbisText = itbisBox.Text.Trim();
            string descuentoText = descuentoBox.Text.Trim();
            string totalGeneralText = totalGeneralBox.Text.Trim();

            if (string.IsNullOrEmpty(descripcion) || string.IsNullOrEmpty(categoria) ||
                string.IsNullOrEmpty(cantidadText) || string.IsNullOrEmpty(precioUnitarioText) ||
                string.IsNullOrEmpty(itbisText) || string.IsNullOrEmpty(descuentoText) ||
                string.IsNullOrEmpty(totalGeneralText))
            {
                MessageBox.Show("Todos los campos son obligatorios.", "Advertencia", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            if (!int.TryParse(cantidadText, out int cantidad))
            {
                MessageBox.Show("La cantidad debe ser un número entero válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(precioUnitarioText, out decimal precioUnitario))
            {
                MessageBox.Show("El precio unitario debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(itbisText, out decimal itbis))
            {
                MessageBox.Show("El ITBIS debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(descuentoText, out decimal descuento))
            {
                MessageBox.Show("El descuento debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            if (!decimal.TryParse(totalGeneralText, out decimal totalGeneral))
            {
                MessageBox.Show("El total general debe ser un número decimal válido.", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            try
            {
                Conexion.ActualizarFactura(Id, descripcion, categoria, cantidad, precioUnitario, itbis, descuento, totalGeneral);
                MessageBox.Show("Factura agregada correctamente.", "Éxito", MessageBoxButtons.OK, MessageBoxIcon.Information);
                Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Ocurrió un error al actualizar la factura: {ex.Message}", "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }
    }
}

```

---

## 📄 Paso 5: Crear el reporte (Reporte1.rdlc y DataSet.xsd)

1. Crea un nuevo archivo `.rdlc` desde la carpeta `Reportes`.
2. Diseña el layout del reporte arrastrando campos desde el `DataSet.xsd`.
3. Asegúrate de que el `DataSet.xsd` esté bien conectado a la base de datos.

📍**Ubicación de diseño visual:** Carpeta `Reportes/`

---

## 🖨️ Paso 6: Crear el formulario que muestra el reporte (ReporteForm.cs)

Este formulario contiene el `ReportViewer`, que permite visualizar y exportar el reporte.

📍**Ubicación del código:** Archivo `ReporteForm.cs`

```csharp
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ReportesProyecto.Reportes
{
    public partial class ReporteForm : Form
    {
        public ReporteForm()
        {
            InitializeComponent();
        }

        private void ReporteForm_Load(object sender, EventArgs e)
        {

            this.reportViewer1.RefreshReport();
        }
    }
}

```
## ✅ Paso a paso: Instalar MySql.Data (mysqlclient) desde NuGet

**🔹 Paso 1: Abrir el Administrador de NuGet**

**Hay dos formas de hacerlo:**

**Opción A (interfaz gráfica):**

- Haz clic derecho sobre tu proyecto en el Explorador de soluciones.

- Selecciona "Administrar paquetes NuGet...".


**Opción B (línea de comandos):**

Ve a Herramientas > Administrador de paquetes NuGet > Consola del Administrador de paquetes.

![alt text](image-1.png)

![alt text](image-3.png)
---


## Paso 2: Buscar el paquete MySql.Data

**Si usas la interfaz gráfica:**
- En la pestaña "Examinar", escribe:

```csharp
MySql.Data
```
- Selecciona el paquete que diga "MySql.Data" (autor: Oracle).

- Haz clic en Instalar.

**Si usas la consola:**

- Escribe y ejecuta este comando:

```mathematica
Install-Package MySql.Data
```
![alt text](image-4.png)
![alt text](image-5.png)
---

## 🔹 Paso 3: Aceptar los términos
Cuando aparezca la ventana emergente, acepta los términos de licencia para continuar con la instalación.

---
## 🔹 Paso 4: Confirmar instalación
**Una vez instalado, verifica:**

- Que el paquete aparece en la pestaña "Instalado".

- Que en el archivo **.csproj** se agregó una línea similar a:

```xml
<PackageReference Include="MySql.Data" Version="8.x.x" />
```
---

## 🔹 Paso 5: Usar en tu código

**Ya puedes usar MySQL en tu código C#. Ejemplo:**

```csharp
using MySql.Data.MySqlClient
```
---

## ¿Qué hace este código?

Este código crea una ventana (formulario) que puede mostrar reportes. Es parte de una aplicación de escritorio en C# utilizando Windows Forms y el componente llamado `ReportViewer`.

---

## Explicación del Código

```csharp
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
```

- ¿Qué es esto?

Estas líneas están incluyendo librerías o módulos que ya vienen con C#. Estas librerías contienen funcionalidades para manejar ventanas, gráficos, textos, datos, etc.

---


```csharp

namespace ReportesProyecto.Reportes
{
```
- ¿Qué es un namespace?

Es como una carpeta virtual que agrupa clases relacionadas. Aquí, el código está dentro del espacio llamado ReportesProyecto.Reportes.

---
```csharp

public partial class ReporteForm : Form
```

- ¿Qué es una clase?

Una clase es una plantilla que describe cómo será un objeto. En este caso, ReporteForm es una clase que representa una ventana con capacidad de mostrar reportes.

**public:** significa que esta clase se puede usar desde otros archivos.

**partial:** indica que la definición de la clase puede estar dividida en varios archivos.

**Form:** significa que esta clase hereda de Form, lo que quiere decir que es un formulario de Windows.

---

```csharp
    {
        public ReporteForm()
        {
            InitializeComponent();
        }
```
- ¿Qué es esto?

Esto es el constructor de la clase. Es lo primero que se ejecuta cuando se crea un objeto de tipo ReporteForm. Dentro del constructor se llama al método InitializeComponent(), que configura la ventana y sus controles.

---

```csharp
        private void ReporteForm_Load(object sender, EventArgs e)
        {
            this.reportViewer1.RefreshReport();
        }
```

- ¿Qué es ReporteForm_Load?

Es un evento que se ejecuta automáticamente cuando la ventana termina de cargarse. Aquí se usa para actualizar el ReportViewer con el método RefreshReport().

---
## Conclusión ##

Este código crea una ventana que contiene un componente visual llamado ReportViewer, y cuando esa ventana se carga, el componente se actualiza para mostrar el reporte. Es útil en sistemas que necesitan mostrar facturas, informes o gráficos generados desde datos.


---

🔧 **Propiedades importantes del ReportViewer**:
- `LocalReport.ReportPath` debe apuntar a `Reportes/Reporte1.rdlc`
- Se debe conectar al `DataSet` que retorna los datos desde la base

---

## 🏁 Paso Final: Ejecutar el proyecto

Presiona **F5** para compilar y ejecutar. Asegúrate de que la conexión a SQL Server esté funcionando y que el reporte esté correctamente enlazado.

---

## 🧠 Recomendaciones

- Usa nombres claros en tus formularios y campos.
- Valida todos los datos antes de insertarlos.
- Asegúrate de probar cada módulo de forma independiente.

---

*Creado para principiantes por ChatGPT con ayuda del usuario*

- Puedes generar facturas para tu empresa o negocio.
- Te permite imprimir o guardar reportes profesionales.
- Aprendes a conectar aplicaciones con bases de datos reales.
- Puedes ofrecer este servicio como programador.

---

¡Listo! Ya sabes cómo crear reportes en **C#**, aunque seas principiante.
