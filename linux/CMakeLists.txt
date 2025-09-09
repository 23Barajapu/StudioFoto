# The Flutter tooling requires that developers have a version of Visual Studio
# installed that includes CMake 3.14 or later. You should not increase this
# version, as doing so will cause the plugin tooling to malfunction
# with older versions of Flutter.
cmake_minimum_required(VERSION 3.14)

# Project-level configuration.
set(PROJECT_NAME "prime_foto_studio")
set(APPLICATION_ID "com.primefotostudio.app")

# Define the application target. To change its name, change PROJECT_NAME above,
# not the value here, or `flutter create` will regenerate the files with your
# change.
add_executable(${PROJECT_NAME}
  "main.cc"
  "my_application.cc"
  "utils.cc"
  "window_configuration.cc"
  "generated_plugin_registrant.cc"
)

# Apply a standard set of build settings. This can be removed for applications
# that need different build settings.
apply_standard_settings(${PROJECT_NAME})

# Add dependency libraries and include directories. Add any application-specific
# dependencies here.
target_link_libraries(${PROJECT_NAME} PRIVATE flutter)
target_link_libraries(${PROJECT_NAME} PRIVATE PkgConfig::GTK)

# Run the Flutter tool portions of the build. This must not be removed.
_apply_standard_settings(${PROJECT_NAME})