#include <flutter_linux/flutter_linux.h>
#include <gtk/gtk.h>

#include "flutter/generated_plugin_registrant.h"
#include "window_configuration.h"

int main(int argc, char** argv) {
  g_autoptr(GtkApplication) app = gtk_application_new(
      "com.primefotostudio.app", G_APPLICATION_FLAGS_NONE);
  g_autoptr(FlEngine) engine = nullptr;
  g_autoptr(FlDartProject) project = nullptr;

  gtk_init(&argc, &argv);

  project = fl_dart_project_new();

  g_autoptr(FlView) view = fl_view_new(project);
  gtk_widget_show(GTK_WIDGET(view));

  g_autoptr(GtkWindow) window =
      GTK_WINDOW(gtk_application_window_new(app));
  gtk_window_set_title(window, "Prime Foto Studio");
  gtk_window_set_default_size(window, 1280, 720);
  gtk_window_set_child(window, GTK_WIDGET(view));

  gtk_window_present(window);

  engine = fl_view_get_engine(view);
  fl_register_plugins(FL_PLUGIN_REGISTRY(engine));

  int result = g_application_run(G_APPLICATION(app), argc, argv);

  return result;
}
