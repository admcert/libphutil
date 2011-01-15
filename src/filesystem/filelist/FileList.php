<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * A list of files, e.g. from command line arguments.
 *
 * @group filesystem
 */
class FileList {

  protected $files = array();
  protected $dirs  = array();

  /**
   * Build a new FileList from an array of paths, e.g. from $argv.
   *
   * @param  list  List of relative or absolute file paths.
   */
  public function __construct($paths) {
    foreach ($paths as $path) {
      $path = Filesystem::resolvePath($path);
      if (is_dir($path)) {
        $path = rtrim($path, '/').'/';
        $this->dirs[$path] = true;
      }
      $this->files[] = $path;
    }
  }


  /**
   * Determine if a path is one of the paths in the list. Note that an empty
   * file list is considered to contain every file.
   *
   * @param  string  Relative or absolute system file path.
   * @param  bool    If true, consider the path to be contained in the list if
   *                 the list contains a parent directory. If false, require
   *                 that the path be part of the list explicitly.
   * @return bool    If true, the file is in the list.
   */
  public function contains($path, $allow_parent_directory = true) {

    if ($this->isEmpty()) {
      return true;
    }

    $path = Filesystem::resolvePath($path);
    if (is_dir($path)) {
      $path .= '/';
    }

    foreach ($this->files as $file) {
      if ($file == $path) {
        return true;
      }
      if ($allow_parent_directory) {
        $len = strlen($file);
        if (isset($this->dirs[$file]) && !strncmp($file, $path, $len)) {
          return true;
        }
      }
    }
    return false;
  }


  /**
   *  Check if the file list is empty -- that is, it contains no files.
   *
   *  @return   bool  If true, the list is empty.
   */
  public function isEmpty() {
    return !$this->files;
  }

}
